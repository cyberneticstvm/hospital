<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\LabRadiology;
use Carbon\Carbon;
use DB;

class LabRadiologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct(){
        $this->middleware('permission:lab-radiology-list|lab-radiology-create|lab-radiology-edit|lab-radiology-delete', ['only' => ['index','store']]);
        $this->middleware('permission:lab-radiology-create', ['only' => ['create','store', 'fetch']]);
        $this->middleware('permission:lab-radiology-edit', ['only' => ['edit','update', 'fetch']]);
        $this->middleware('permission:lab-radiology-delete', ['only' => ['destroy']]);
   }

    public function index()
    {
        $labs = LabRadiology::leftJoin('patient_medical_records AS m', 'lab_radiologies.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'm.patient_id', '=', 'p.id')->leftJoin('doctors AS d', 'm.doctor_id', '=', 'd.id')->leftJoin('lab_types as t', 't.id', '=', 'lab_radiologies.lab_type_id')->selectRaw("lab_radiologies.id, lab_radiologies.medical_record_id, p.patient_name, p.patient_id, d.doctor_name, DATE_FORMAT(lab_radiologies.created_at, '%d/%b/%Y') AS ldate, t.lab_type_name")->get();
        return view('lab.radiology.index', compact('labs'));
    }

    public function fetch(){
        $lab_record = [];
        return view('lab.radiology.fetch', compact('lab_record'));
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
        $input = $request->all();
        try{
            if($input['test_id']):
                for($i=0; $i<count($input['test_id']); $i++):
                    if($input['test_id'][$i] > 0):
                        DB::table('lab_radiologies')->insert([
                            'medical_record_id' => $request->medical_record_id,
                            'lab_type_id' => $input['test_id'][$i],
                            'tested_from' => $input['tested_from'][$i],
                            'created_by' => $request->user()->id,
                            'updated_by' => $request->user()->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                    endif;
                endfor;
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('lab.radiology.index')->with('success','Lab Record created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'medical_record_number' => 'required',
        ]);
        $mrecord = DB::table('patient_medical_records')->find($request->medical_record_number);
        $labtests = DB::table('lab_types')->where('category_id', 2)->get();
        if($mrecord):
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('lab.radiology.create', compact('mrecord', 'patient', 'doctor', 'age', 'labtests'));
        else:
            return redirect("/lab/radiology/fetch/")->withErrors('No records found.');
        endif;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lab_records = LabRadiology::where('medical_record_id', $id)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $labtests = DB::table('lab_types')->where('category_id', 2)->get();
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('lab.radiology.edit', compact('lab_records', 'patient', 'mrecord', 'labtests', 'doctor', 'age'));
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
