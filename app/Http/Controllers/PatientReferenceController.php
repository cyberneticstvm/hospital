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
    function __construct()
    {
         $this->middleware('permission:patient-reference-list|patient-reference-create|patient-reference-edit|patient-reference-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-reference-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-reference-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-reference-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = DB::table('patient_registrations')->rightJoin('patient_references', 'patient_references.patient_id', '=', 'patient_registrations.id')->leftJoin('doctors', 'patient_references.doctor_id', '=', 'doctors.id')->select('patient_references.id as reference_id', 'patient_registrations.patient_id as pno', 'patient_registrations.patient_name as pname', 'patient_references.doctor_fee', 'doctors.doctor_name')->get();
        //dd($patients);
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
        return view('consultation.create-patient-reference', compact('patient', 'doctors', 'departments'));
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
            'symptoms' => 'required',
        ]);
        $input = $request->all();
        $doctor = doctor::find($request->doctor_id);
        $input['patient_id'] = $request->get('pid');
        $input['doctor_fee'] = $doctor->doctor_fee;
        $input['created_by'] = $request->user()->id;
        $reference = PRef::create($input);
        PReg::where(['id' => $request->pid])->update(['is_doctor_assigned' => 1]);
        return redirect()->route('patient.index')->with('success','Doctor created successfully');
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
        //
    }
}
