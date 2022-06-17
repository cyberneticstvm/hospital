<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admission;
use Carbon\Carbon;
use DB;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:admission-list|admission-create|admission-edit|admission-delete', ['only' => ['index','store']]);
         $this->middleware('permission:admission-create', ['only' => ['create','store']]);
         $this->middleware('permission:admission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:admission-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $admissions = DB::table('admissions as a')->leftJoin('patient_registrations as p', 'a.patient_id', '=', 'p.id')->leftJoin('doctors as d', 'a.doctor_id', '=', 'd.id')->leftJoin('rooms as rm', 'a.room_type', '=', 'rm.id')->select('a.id', 'p.patient_name', 'p.patient_id', 'd.doctor_name', 'a.medical_record_id', 'a.admission_date', 'rm.room_type')->orderBy('a.id', 'desc')->get();
        return view('admission.index', compact('admissions'));
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
        //
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
        $rtypes = DB::table('rooms')->get();
        $admission = Admission::find($id);
        $patient = DB::table('patient_registrations')->find($admission->patient_id);
        $doctor = DB::table('doctors')->find($admission->doctor_id);
        return view('admission.edit', compact('admission', 'rtypes', 'patient', 'doctor'));
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
            'room_type' => 'required',
            'room_number' => 'required',
            'admission_date' => 'required',
            'bystander_name' => 'required',
            'bystander_contact_number' => 'required',
            'patient_bystander_relation' => 'required'
        ]);
        $input = $request->all();
        $input['admission_date'] = (!empty($request->admission_date)) ? Carbon::createFromFormat('d/M/Y', $request['admission_date'])->format('Y-m-d') : NULL;
        $input['updated_by'] = $request->user()->id;
        $admission = Admission::find($id);
        $admission->update($input);        
        return redirect()->route('admission.index')->with('success','Admission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admission = Admission::find($id);
        $admission->delete();
        DB::table('patient_medical_records')->where('id', $admission->medical_record_id)->update(['is_admission' => 0]);
        return redirect()->route('admission.index')
                        ->with('success','Admission deleted successfully');
    }
}
