<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PatientMedicalRecord as PMRecord;
use App\Models\User;
use Carbon\Carbon;
use DB;

class PatientMedicalRecordController extends Controller
{
    private $branch;

    /*function __construct()
    {
        $this->middleware('permission:patient-medical-record-list|patient-medical-record-create|patient-medical-record-edit|patient-medical-record-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:patient-medical-record-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient-medical-record-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:patient-medical-record-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medical_records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('doctors as doc', 'pmr.doctor_id', '=', 'doc.id')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->leftJoin('consultation_types as c', 'c.id', '=', 'pref.consultation_type')->leftJoin('appointments as a', 'pref.appointment_id', '=', 'a.id')->leftJoin('inhouse_camps as ic', 'a.camp_id', '=', 'ic.id')->select('pmr.id', 'pmr.mrn', DB::raw("CASE WHEN (a.camp_id > 0 AND pref.consultation_type = 4) THEN CONCAT_WS('', pr.patient_name, '- (', ic.name, ')') WHEN pref.consultation_type = 4 THEN CONCAT_WS(' ', pr.patient_name, '- (Camp)') WHEN (pref.consultation_type = 2 OR pref.consultation_type = 3) THEN CONCAT_WS(' ', pr.patient_name, '- (Cert)') ELSE pr.patient_name END AS patient_name"), 'pr.patient_id', 'pr.age', 'doc.doctor_name', 'pmr.status', 'ic.name as campname', 'pmr.diagnosis', DB::Raw("DATE_FORMAT(pmr.created_at, '%d/%b/%Y') AS rdate, IFNULL(DATE_FORMAT(pmr.review_date, '%d/%b/%Y'), '--') AS review_date"), DB::raw("CASE WHEN pmr.updated_at IS NULL THEN 'no' ELSE 'yes' END AS cstatus"))->where('pmr.branch', $this->branch)->whereDate('pmr.created_at', Carbon::today())->whereIn('pref.consultation_type', [1, 3, 7, 4])->when(Auth::user()->roles->first()->name != 'Admin', function ($query) {
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->orderByDesc('pmr.id')->get();
        $ccount = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->where('pmr.branch', $this->branch)->whereDate('pmr.created_at', Carbon::today())->whereIn('pref.consultation_type', [1, 3, 7, 4])->whereNull('pmr.updated_at')->when(Auth::user()->roles->first()->name != 'Admin', function ($query) {
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->count();
        $ccount1 = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->where('pmr.branch', $this->branch)->whereDate('pmr.created_at', Carbon::today())->whereIn('pref.consultation_type', [1, 3, 7, 4])->when(Auth::user()->roles->first()->name != 'Admin', function ($query) {
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->whereNotNull('pmr.updated_at')->count();
        return view('consultation.index', compact('medical_records', 'ccount', 'ccount1'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->symptom_id) {
            echo "Please choose symptom";
            die;
        }
        if (!$request->diagnosis_id) {
            echo "Please choose diagnosis";
            die;
        }
        if (!$request->doctor_recommondations) {
            echo "Please enter doctor recommondations";
            die;
        }
        $input = $request->all();
        $odospoints = json_decode(stripslashes($input['odospoints']), true);

        $input['review_date'] = ($input['review_date']) ? Carbon::createFromFormat('d/M/Y', $request['review_date'])->format('Y-m-d') : NULL;
        $input['symptoms'] = implode(',', $request->symptom_id);
        $input['diagnosis'] = implode(',', $request->diagnosis_id);
        $input['created_by'] = $request->user()->id;

        $input['medicine'] = $request->medicine_id;
        $input['dosage'] = $request->dosage;
        $input['dosage1'] = $request->dosage1;

        //$input['is_admission'] = $request->is_admission;

        try {
            $record = PMRecord::create($input);

            if ($input['medicine']):
                for ($i = 0; $i < count($input['medicine']); $i++):
                    if ($input['medicine'][$i] > 0):
                        $product = DB::table('products')->find($input['medicine'][$i]);
                        DB::table('patient_medicine_records')->insert([
                            'medical_record_id' => $record->id,
                            'mrn' => $request->mrn,
                            'medicine' => $input['medicine'][$i],
                            'dosage' => $input['dosage'][$i],
                            'qty' => $input['qty'][$i],
                            'tax_percentage' => $product->tax_percentage,
                            'notes' => $input['notes'][$i],
                            'updated_by' => $request->user()->id,
                            'updated_at' => Carbon::now(),
                        ]);
                    endif;
                endfor;
            endif;
            if ($odospoints):
                foreach ($odospoints as $value):
                    DB::table('patient_medical_records_vision')->insert([
                        'medical_record_id' => $record->id,
                        'description' => $value['description'],
                        'color' => $value['color'],
                        'img_type' => $value['type'],
                    ]);
                endforeach;
            endif;
            if (isset($input['retina_img'])):
                for ($i = 0; $i < count($input['retina_img']); $i++):
                    DB::table('patient_medical_records_retina')->insert([
                        'medical_record_id' => $record->id,
                        'retina_img' => $input['retina_img'][$i],
                        'description' => $input['retina_desc'][$i],
                        'retina_type' => $input['retina_type'][$i],
                    ]);
                endfor;
            endif;
            echo "success";
        } catch (Exception $e) {
            throw $e;
        }

        //return redirect()->route('consultation.index')->with('success','Medical Record created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PMRecord $precord, $id)
    {
        $record = PMRecord::find($id);
        if (Auth::user()->roles->first()->name != 'Admin'):
            if (!Gate::allows('update-medical-record', $record)) {
                abort(403, 'Oops.. You are not allowed to perform this action!');
            }
        endif;
        $tests = DB::table('tests')->orderBy('name')->get();
        $tests_advised = DB::table('tests_adviseds')->where('medical_record_id', $id)->get();
        $patient = DB::table('patient_registrations')->find($record->patient_id);
        $symptoms = DB::table('symptoms')->get();
        $diagnosis = DB::table('diagnosis')->get();
        $medicines = DB::table('products')->get();
        $mtypes = DB::table('medicine_types')->get();
        $mrns = DB::table('patient_references')->where('patient_id', $patient->id)->orderByDesc('id')->get();
        //$medicines = DB::table('products as p')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.id', DB::raw("CONCAT_WS(' - ', p.product_name, t.name) AS product_name"))->get();
        $dosages = DB::table('dosages')->get();
        $doctor = DB::table('doctors')->find($record->doctor_id);
        //$spectacle = DB::table('spectacles')->where('patient_id', $record->patient_id)->orderByDesc('id')->first();
        $spectacle = DB::table('spectacles')->where('medical_record_id', $id)->first();
        $medicine_record = DB::table('patient_medicine_records')->where('medical_record_id', $id)->get();
        $retina_od = DB::table('patient_medical_records_retina')->where('medical_record_id', $id)->where('retina_type', 'od')->get();
        $retina_os = DB::table('patient_medical_records_retina')->where('medical_record_id', $id)->where('retina_type', 'os')->get();
        $vision = DB::table('patient_medical_records_vision')->where('medical_record_id', $id)->get();
        $vextras = DB::table('vision_extras')->where('cat_id', '>', 0)->get();
        return view('consultation.edit-medical-records', compact('record', 'patient', 'symptoms', 'doctor', 'diagnosis', 'medicines', 'dosages', 'medicine_record', 'spectacle', 'retina_od', 'retina_os', 'vision', 'vextras', 'mtypes', 'mrns', 'tests', 'tests_advised'));
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
        echo "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PMRecord::find($id)->delete();
        return redirect()->route('consultation.index')
            ->with('success', 'Medical Record deleted successfully');
    }
}
