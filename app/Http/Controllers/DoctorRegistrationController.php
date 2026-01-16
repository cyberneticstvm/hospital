<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\doctor;
use App\Models\doctor_has_department;
use Carbon\Carbon;
use DB;

class DoctorRegistrationController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:doctor-list|doctor-create|doctor-edit|doctor-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:doctor-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:doctor-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:doctor-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = doctor::orderBy('doctor_name', 'ASC')->get();
        $departments = DB::table('departments')->get();
        $doctor_depts = DB::table('doctor_has_departments')->get();
        return view('doctor.index', compact('doctors', 'departments', 'doctor_depts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = DB::table('departments')->get();
        $types = DB::table('types')->where('category', 'doctor')->get();
        return view('doctor.create', compact('departments', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = $request->get('department_id');
        $this->validate($request, [
            'doctor_name' => 'required',
            'date_of_join' => 'required',
            'designation' => 'required',
            'doctor_fee' => 'required',
            'department_id' => 'required',
            'doc_type' => 'required',
        ]);
        $input = $request->all();
        $input['date_of_join'] = Carbon::createFromFormat('d/M/Y', $request['date_of_join'])->format('Y-m-d');
        $doctor = doctor::create($input);
        foreach ($items as $key => $value):
            DB::table('doctor_has_departments')->insert([
                'doctor_id' => $doctor->id,
                'department_id' => $value
            ]);
        endforeach;
        return redirect()->route('doctor.index')->with('success', 'Doctor created successfully');
        //$departments = $doctor->doctor_has_departments()->createMany($items);
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
        $doctor = doctor::find($id);
        $departments = DB::table('departments')->get();
        $doctor_depts = doctor_has_department::select('*')->where('doctor_id', '=', $id)->get();
        $types = DB::table('types')->where('category', 'doctor')->get();
        return view('doctor.edit', compact('doctor', 'doctor_depts', 'departments', 'types'));
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
        $items = $request->get('department_id');
        $this->validate($request, [
            'doctor_name' => 'required',
            'date_of_join' => 'required',
            'designation' => 'required',
            'doctor_fee' => 'required',
            'department_id' => 'required',
            'doc_type' => 'required',
        ]);
        $input = $request->all();
        $input['date_of_join'] = Carbon::createFromFormat('d/M/Y', $request['date_of_join'])->format('Y-m-d');
        $doctor = doctor::find($id);
        $doctor->update($input);
        DB::table("doctor_has_departments")->where('doctor_id', $id)->delete();
        foreach ($items as $key => $value):
            DB::table('doctor_has_departments')->insert([
                'doctor_id' => $id,
                'department_id' => $value
            ]);
        endforeach;
        return redirect()->route('doctor.index')->with('success', 'Doctor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        doctor::find($id)->delete();
        return redirect()->route('doctor.index')
            ->with('success', 'Doctor deleted successfully');
    }
}
