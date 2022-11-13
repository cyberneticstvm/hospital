<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\appointment;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch, $doctors, $branches;

    function __construct(){
        $this->middleware('permission:appointment-list|appointment-create|appointment-edit|appointment-delete', ['only' => ['index','store']]);
        $this->middleware('permission:appointment-create', ['only' => ['create','store']]);
        $this->middleware('permission:appointment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:appointment-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
        $this->doctors = DB::table('doctors')->get();
        $this->branches = DB::table('branches')->get();
    }
    public function index()
    {
        $appointments = Appointment::leftJoin('doctors as d', 'd.id', '=', 'appointments.doctor')->select('appointments.*', DB::RAW("DATE_FORMAT(appointments.appointment_date, '%d/%b/%Y') AS adate"), 'd.doctor_name')->where('appointments.branch', $this->branch)->where('appointments.appointment_date', '>=', Carbon::today())->orderByDesc('appointments.appointment_date')->get();
        return view('appointment.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patient = [];
        $doctors = $this->doctors; $branches = $this->branches;
        return view('appointment.create', compact('patient', 'doctors', 'branches'));
    }

    public function gettime($date, $branch){
        $data = DB::table('products')->select('id', 'product_name as name')->where('medicine_type', $type)->get();
        return response()->json($data);
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
    public function show(Request $request)
    {
        $this->validate($request, [
            'patient_id' => 'required',
        ]);
        $input = $request->all();
        $patient = DB::table('patient_registrations as p')->select('p.*', DB::raw("DATE_FORMAT(p.dob, '%d/%b/%Y') AS bdate"))->where('id', $request->patient_id)->first();
        if($patient):
            $doctors = $this->doctors; $branches = $this->branches;
            return view('appointment.create', compact('patient', 'doctors', 'branches'));
        else:
            return redirect("/appointment/")->withErrors('No records found.');
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
