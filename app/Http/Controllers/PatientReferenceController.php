<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PatientReference as PRef;
use App\Models\PatientRegistrations as PReg;
use App\Models\Appointment;
use App\Models\doctor;
use App\Models\DoctorOptometrist;
use App\Models\InhouseCamp;
use App\Models\RoyaltyCard;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class PatientReferenceController extends Controller
{
    private $branch;
    function __construct()
    {
        $this->middleware('permission:patient-reference-list|patient-reference-create|patient-reference-edit|patient-reference-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:patient-reference-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient-reference-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:patient-reference-delete', ['only' => ['destroy']]);
        $this->middleware('permission:doctor-optometrist-view', ['only' => ['optoToDoc']]);
        $this->middleware('permission:doctor-optometrist-create', ['only' => ['optoToDoc', 'optoToDocUpdate']]);
        $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function getDoctorFee($pid, $fee, $ctype)
    {
        $doc_fee = 0.00;
        $days = DB::table('settings')->where('id', 1)->value('consultation_fee_days');
        //$date_diff = PRef::where('patient_id', $pid)->select(DB::raw("IFNULL(DATEDIFF(now(), created_at), 0) as days"))->latest()->value('days');
        $date_diff = PRef::where('patient_id', $pid)->select(DB::raw("IFNULL(DATEDIFF(now(), created_at), 0) as days, status, consultation_type"))->latest()->first();
        $diff = ($date_diff && $date_diff['days'] > 0) ? $date_diff['days'] : 0;
        $cstatus = ($date_diff && $date_diff['status'] > 0) ? $date_diff['status'] : 0;
        if ($diff == 0 || $diff > $days || ($diff < $days && $cstatus == 0) || ($diff < $days && $date_diff['consultation_type'] == 2) || ($diff < $days && $date_diff['consultation_type'] == 4) || ($diff < $days && $date_diff['consultation_type'] == 5) || ($diff < $days && $date_diff['consultation_type'] == 6) || ($diff < $days && $date_diff['consultation_type'] == 7)) :
            // $diff = 0 means first consultation, $diff<$days and cstatus means the patient might be cancelled the consultation
            $doc_fee = $fee;
        endif;
        if ($ctype == 2 || $ctype == 4 || $ctype == 5 || $ctype == 6 || $ctype == 7 || $ctype == 8) :
            $doc_fee = 0.00; // ctype 2/4/5 means purpose of visit is Certificate/Camp/Vision Examination and no consultation fee for that.
        endif;
        return $doc_fee;
    }

    private function getRegistrationFee($branch, $ctype)
    {
        $fee = DB::table('branches')->where('id', $branch)->value('registration_fee');
        if ($ctype == 1 || $ctype == 3) :
            return $fee;
        endif;
        return 0;
    }
    public function index()
    {
        $doc = DoctorOptometrist::where('optometrist_id', Auth::id())->whereDate('created_at', Carbon::today())->latest()->first();
        $patients = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->leftJoin('doctors', 'pr.doctor_id', '=', 'doctors.id')->leftJoin('patient_registrations as p', 'pr.patient_id', '=', 'p.id')->select('pr.id as reference_id', 'pr.sms', 'pr.status', 'pmr.id as medical_record_id', 'p.patient_name as pname', 'p.patient_id as pno', 'doctors.doctor_name', DB::Raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate"))->where('pr.branch', $this->branch)->whereDate('pr.created_at', Carbon::today())->orderByDesc('pmr.id')->when(Auth::user()->roles->first()->name == 'Doctor', function ($query) {
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->when(Auth::user()->roles->first()->name == 'Optometriest', function ($query) use ($doc) {
            return $query->where('pmr.doctor_id', $doc?->doctor_id ?? 0);
        })->get();
        return view('consultation.patient-reference', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $patient = Preg::find($id);
        $doctors = DB::table('doctors')->get();
        $departments = DB::table('departments')->get();
        $ctypes = DB::table('consultation_types')->get();
        $review = 'no';
        $appid = $patient->appointment_id;
        $camps = InhouseCamp::where('status', 1)->get();
        $campid = 0;
        $campid = ($appid > 0) ? Appointment::where('id', $appid)->value('camp_id') : 0;
        $rcards = RoyaltyCard::all();
        return view('consultation.create-patient-reference', compact('patient', 'doctors', 'departments', 'ctypes', 'review', 'appid', 'camps', 'campid', 'rcards'));
    }

    public function reopen($id, $appid)
    {
        $patient = Preg::find($id);
        $doctors = DB::table('doctors')->get();
        $departments = DB::table('departments')->get();
        $ctypes = DB::table('consultation_types')->get();
        $review = 'yes';
        $camps = InhouseCamp::where('status', 1)->get();
        $campid = ($appid > 0) ? Appointment::where('id', $appid)->value('camp_id') : 0;
        $rcards = RoyaltyCard::all();
        return view('consultation.create-patient-reference', compact('patient', 'doctors', 'departments', 'ctypes', 'review', 'appid', 'camps', 'campid', 'rcards'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required',
            'department_id' => 'required',
            'consultation_type' => 'required'
        ]);
        try {
            $secret = Helper::apiSecret();
            $vcode = $request->rc_number;
            if ($vcode && $request->rc_type == 2):
                $url = Helper::api_url() . "/api/vehicle/$vcode/$secret";
                $json = file_get_contents($url);
                $vehicle = json_decode($json);
                if ($vehicle->status):
                    if ($vehicle->vstatus == 'Inactive'):
                        exit("Provided vehicle is inactive or does not exists");
                    else:
                        $input['rc_type'] = $request->rc_type;
                        $input['rc_number'] = $request->rc_number;
                    endif;
                endif;
            endif;
            $input = $request->all();
            $doctor = doctor::find($request->doctor_id);
            $input['patient_id'] = $request->get('pid');
            $input['doctor_fee'] = $this->getDoctorFee($request->get('pid'), $doctor->doctor_fee, $request->consultation_type);
            if ($input['rc_number']):
                $input['discount'] = $input['doctor_fee'];
            endif;
            $input['created_by'] = $request->user()->id;
            $input['status'] = 1; //active
            $reg_fee = $this->getRegistrationFee($this->branch, $request->consultation_type);
            $input['branch'] = $this->branch;
            $token = PRef::where('department_id', $request->department_id)->where('branch', $this->branch)->whereDate('created_at', Carbon::today())->max('token');
            $input['token'] = ($token > 0) ? $token + 1 : 1;
            DB::transaction(function () use ($input, $request, $reg_fee) {
                $reference = PRef::create($input);
                PReg::where(['id' => $request->pid])->update(['is_doctor_assigned' => 1, 'registration_fee' => $reg_fee]);
                Appointment::where(['id' => $request->appointment_id])->update(['medical_record_id' => $reference->id]);
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", "Royalty Card information not found!")->withInput($request->all());
        }

        return redirect()->route('consultation.patient-reference')->with('success', 'Doctor Assigned successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reference = PRef::find($id);
        $patient = PReg::find($reference->patient_id);
        $symptoms = DB::table('symptoms')->get();
        $diagnosis = DB::table('diagnosis')->get();
        $medicines = DB::table('products')->get();
        $dosages = DB::table('dosages')->get();
        $doctor = doctor::find($reference->doctor_id);
        return view('consultation.medical-records', compact('reference', 'patient', 'symptoms', 'doctor', 'diagnosis', 'medicines', 'dosages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doctors = DB::table('doctors')->get();
        $departments = DB::table('departments')->get();
        $reference = PRef::find($id);
        $patient = PReg::find($reference->patient_id);
        $ctypes = DB::table('consultation_types')->get();
        $camps = InhouseCamp::where('status', 1)->get();
        $rcards = RoyaltyCard::all();
        return view('consultation.edit-patient-reference', compact('doctors', 'departments', 'reference', 'patient', 'ctypes', 'camps', 'rcards'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'doctor_id' => 'required',
            'department_id' => 'required',
            'consultation_type' => 'required'
        ]);
        try {
            $input = $request->all();
            $secret = Helper::apiSecret();
            $vcode = $request->rc_number;
            if ($vcode && $request->rc_type == 2):
                $url = Helper::api_url() . "/api/vehicle/" . $vcode . "/" . $secret;
                $json = file_get_contents($url);
                $vehicle = json_decode($json);
                if ($vehicle->status):
                    if ($vehicle->vstatus == 'Inactive'):
                        throw new Exception("Provided vehicle is inactive or does not exists");
                    else:
                        $input['rc_type'] = $request->rc_type;
                        $input['rc_number'] = $request->rc_number;
                    endif;
                endif;
            endif;
            $doctor = doctor::find($request->doctor_id);
            $input['patient_id'] = $request->get('pid');
            $reference = PRef::find($id);
            //if($reference->getOriginal('doctor_id') == $request->doctor_id):
            //$input['doctor_fee'] = $reference->getOriginal('doctor_fee');
            //else:
            $input['doctor_fee'] = $this->getDoctorFee($request->get('pid'), $doctor->doctor_fee, $request->consultation_type);
            if ($input['rc_number']):
                $input['discount'] = $input['doctor_fee'];
            endif;
            //endif;
            $input['created_by'] = $reference->getOriginal('created_by');
            $input['branch'] = $reference->getOriginal('branch');
            $input['review'] = $reference->getOriginal('review');
            $input['appointment_id'] = $reference->getOriginal('appointment_id');
            $input['status'] = ($request->status) ? 0 : 1;
            $reg_fee = $this->getRegistrationFee($reference->getOriginal('branch'), $request->consultation_type);
            $token = $reference->getOriginal('token'); //PRef::where('department_id', $request->department_id)->where('branch', $request->session()->get('branch'))->whereDate('created_at', Carbon::today())->max('token');
            //$input['token'] = ($token > 0) ? $token+1 : 1;
            $reference->update($input);
            DB::table('patient_medical_records')->where('mrn', $id)->update(['status' => $input['status']]);
        } catch (Exception $e) {
            return redirect()->back()->with("error", "Royalty Card information not found!")->withInput($request->all());
        }
        return redirect()->route('consultation.patient-reference')->with('success', 'Record Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reference = PRef::find($id);
        PReg::where(['id' => $reference->patient_id])->update(['is_doctor_assigned' => 0]);
        PRef::find($id)->delete();
        return redirect()->route('consultation.patient-reference')
            ->with('success', 'Record deleted successfully');
    }

    public function optoToDoc()
    {
        $doctors = DB::table('doctors')->get();
        $optos = User::role('Optometriest')->get();
        $data = DoctorOptometrist::whereDate('created_at', Carbon::today())->where('branch_id', $this->branch)->get();
        return view('consultation.doctor-optometrist', compact('doctors', 'optos', 'data'));
    }

    public function optoToDocUpdate(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required',
            'optometrist_id' => 'required',
        ]);
        DoctorOptometrist::create([
            'branch_id' => $this->branch,
            'doctor_id' => $request->doctor_id,
            'optometrist_id' => $request->optometrist_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()->route('doc.opto')
            ->with('success', 'Optometrist assigned successfully');
    }
}
