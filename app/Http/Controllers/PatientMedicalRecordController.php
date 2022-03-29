<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PatientMedicalRecord as PMRecord;
use Carbon\Carbon;
use DB;

class PatientMedicalRecordController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:patient-medical-record-list|patient-medical-record-create|patient-medical-record-edit|patient-medical-record-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-medical-record-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-medical-record-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-medical-record-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medical_records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('doctors as doc', 'pmr.doctor_id', '=', 'doc.id')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->select('pmr.id', 'pmr.mrn', 'pr.patient_name', 'pr.patient_id', 'doc.doctor_name', "pmr.review_date as rdate")->get();
        return view('consultation.index', compact('medical_records'));
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
        $this->validate($request, [
            'symptom_id' => 'required',
            'patient_complaints' => 'required',
            'diagnosis_id' => 'required',
            'doctor_findings' => 'required',
            'doctor_recommondations' => 'required',
        ]);
        $input = $request->all();
        $input['review_date'] = Carbon::createFromFormat('d/M/Y', $request['review_date'])->format('Y-m-d');
        $input['symptoms'] = implode(',', $request->symptom_id);
        $input['diagnosis'] = implode(',', $request->diagnosis_id);
        $input['created_by'] = $request->user()->id;
        $record = PMRecord::create($input);        

        $input['medicine'] = $request->medicine_id;
        $input['dosage'] = $request->dosage;
        $input['dosage1'] = $request->dosage1;

        for($i=0; $i<count($input['medicine']); $i++):
            if($input['medicine'][$i] > 0):
                DB::table('patient_medicine_records')->insert([
                    'medical_record_id' => $record->id,
                    'mrn' => $request->mrn,
                    'medicine' => $input['medicine'][$i],
                    'dosage' => $input['dosage'][$i],
                    'dosage1' => $input['dosage1'][$i],
                ]);
            endif;
        endfor;
        
        return redirect()->route('consultation.index')->with('success','Medical Record created successfully');
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
    public function edit($id)
    {
        //
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
        //
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
                        ->with('success','Medical Record deleted successfully');
    }
}
