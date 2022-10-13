<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PatientReference as PRef;
use App\Models\PatientRegistrations as PReg;
use App\Models\doctor;
use Carbon\Carbon;
use DB;

class PatientReferenceController extends Controller
{
    private $branch;
    function __construct()
    {
         $this->middleware('permission:patient-reference-list|patient-reference-create|patient-reference-edit|patient-reference-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-reference-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-reference-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-reference-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function getDoctorFee($pid, $fee, $ctype){
        $doc_fee = 0.00;
        $days = DB::table('settings')->where('id', 1)->value('consultation_fee_days');
        //$date_diff = PRef::where('patient_id', $pid)->select(DB::raw("IFNULL(DATEDIFF(now(), created_at), 0) as days"))->latest()->value('days');
        $date_diff = PRef::where('patient_id', $pid)->select(DB::raw("IFNULL(DATEDIFF(now(), created_at), 0) as days"))->first();
        $date_diff = ($date_diff && $date_diff['days'] > 0) ? $date_diff['days'] : 0;
        if($date_diff == 0 || $date_diff > $days): // $date_diff = 0 means first consultation
            $doc_fee = $fee; 
        endif;
        return ($ctype == 2 || $ctype == 4) ? 0.00 : $doc_fee; // ctype 2/4 means purpose of visit is Certificate/Camp and no consultation fee for that.
    }

    public function index()
    {
        $patients = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->leftJoin('doctors', 'pr.doctor_id', '=', 'doctors.id')->leftJoin('patient_registrations as p', 'pr.patient_id', '=', 'p.id')->select('pr.id as reference_id', 'pr.status', 'pmr.id as medical_record_id', 'p.patient_name as pname', 'p.patient_id as pno', 'doctors.doctor_name', DB::Raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate"))->where('pr.branch', $this->branch)->whereDate('pr.created_at', Carbon::today())->orderByDesc('pmr.id')->get();
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
        return view('consultation.create-patient-reference', compact('patient', 'doctors', 'departments', 'ctypes'));
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
        $input = $request->all();
        $doctor = doctor::find($request->doctor_id);
        $input['patient_id'] = $request->get('pid');
        $input['doctor_fee'] = $this->getDoctorFee($request->get('pid'), $doctor->doctor_fee, $request->consultation_type);
        $input['created_by'] = $request->user()->id;
        $input['status'] = 1; //active
        $input['branch'] = $this->branch;
        $token = PRef::where('department_id', $request->department_id)->where('branch', $this->branch)->whereDate('created_at', Carbon::today())->max('token');
        $input['token'] = ($token > 0) ? $token+1 : 1;
        $reference = PRef::create($input);
        PReg::where(['id' => $request->pid])->update(['is_doctor_assigned' => 1]);
        return redirect()->route('consultation.patient-reference')->with('success','Doctor Assigned successfully');
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
        return view('consultation.edit-patient-reference', compact('doctors', 'departments', 'reference', 'patient', 'ctypes'));
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
        $input = $request->all();
        $doctor = doctor::find($request->doctor_id);
        $input['patient_id'] = $request->get('pid');
        $reference = PRef::find($id);
        //if($reference->getOriginal('doctor_id') == $request->doctor_id):
            //$input['doctor_fee'] = $reference->getOriginal('doctor_fee');
        //else:
            $input['doctor_fee'] = $this->getDoctorFee($request->get('pid'), $doctor->doctor_fee, $request->consultation_type);
        //endif;
        $input['created_by'] = $reference->getOriginal('created_by');
        $input['branch'] = $reference->getOriginal('branch');
        $input['status'] = ($request->status) ? 0 : 1;
        $token = $reference->getOriginal('token');//PRef::where('department_id', $request->department_id)->where('branch', $request->session()->get('branch'))->whereDate('created_at', Carbon::today())->max('token');
        //$input['token'] = ($token > 0) ? $token+1 : 1;
        $reference->update($input);
        DB::table('patient_medical_records')->where('mrn', $id)->update(['status' => $input['status']]);
        return redirect()->route('consultation.patient-reference')->with('success','Doctor Updated successfully');
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
                        ->with('success','Record deleted successfully');
    }
}
