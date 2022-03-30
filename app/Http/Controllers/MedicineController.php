<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Medicine;
use Carbon\Carbon;
use DB;

class MedicineController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:patient-medicine-record-list|patient-medicine-record-create|patient-medicine-record-edit|patient-medicine-record-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-medicine-record-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-medicine-record-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-medicine-record-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('patient_medical_records as pmr', 'm.medical_record_id', '=', 'pmr.id')->leftJoin('patient_registrations as p', 'p.id', '=', 'pmr.patient_id')->leftJoin('doctors as doc', 'pmr.doctor_id', '=', 'doc.id')->select('m.id', 'm.mrn', 'p.patient_name', 'p.patient_id', 'doc.doctor_name',)->get()->groupBy('m.mrn');

        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $medical_record = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id);
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);

        return view('medicine.create', compact('medical_record', 'patient', 'doctor'));
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
