<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch, $doctors, $branches, $settings;

    function __construct(){
        $this->middleware('permission:appointment-list|appointment-create|appointment-edit|appointment-delete', ['only' => ['index','store']]);
        $this->middleware('permission:appointment-create', ['only' => ['create','store']]);
        $this->middleware('permission:appointment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:appointment-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
        $this->doctors = DB::table('doctors')->get();
        $this->branches = DB::table('branches')->get();
        $this->settings = DB::table('settings')->selectRaw("TIME_FORMAT(appointment_from_time, '%h:%i %p') AS from_time, TIME_FORMAT(appointment_to_time, '%h:%i %p') AS to_time, appointment_interval AS ti")->where('id', 1)->first();
    }
    public function index()
    {
        $appointments = Appointment::leftJoin('doctors as d', 'd.id', '=', 'appointments.doctor')->select('appointments.*', DB::RAW("DATE_FORMAT(appointments.appointment_date, '%d/%b/%Y') AS adate"), 'd.doctor_name')->where('appointments.branch', $this->branch)->where('appointments.appointment_date', '>=', Carbon::today())->where('appointments.status', 1)->orderByDesc('appointments.appointment_date')->get();
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

    public function gettime($date, $branch, $doctor){
        $params = $this->settings; $today = Carbon::today(); $op = "<option value=''>Select</option>";
        $start = number_format(date('H', strtotime($params->from_time)), 0);
        $end = number_format(date('H', strtotime($params->to_time)), 0);
        $start = ($date > $today) ? $start : number_format(date('H'), 0);
        for($i=$start; $i<=$end; $i++):
            for($j=0; $j<=60-$params->ti; $j+=$params->ti):
                $val = $i.':'.$j; $val = date("h:i A", strtotime($val));
                $time = Carbon::parse($val)->toTimeString();
                $dis = DB::table('appointments')->selectRaw("TIME_FORMAT(appointment_time, '%h:%i %p') AS btime")->where('branch', $branch)->where('doctor', $doctor)->whereDate('appointment_date', $date)->whereTime('appointment_time', $time)->where('status', 1)->first();
				$dis = ($dis && $dis->btime) ? "disabled" : "";
				$op .= "<option value='$val' $dis>".$val."</option>";
            endfor;
        endfor;
        echo $op;
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
            'patient_name' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'mobile_number' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'branch' => 'required',
            'doctor' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        $input['appointment_date'] = (!empty($request->appointment_date)) ? Carbon::createFromFormat('d/M/Y', $request->appointment_date)->format('Y-m-d') : NULL;
        $input['appointment_time'] = Carbon::createFromFormat('h:i A', $request->appointment_time)->format('H:i:s');
        try{
            $apo = Appointment::create($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('appointment.index')->with('success','Appointment created successfully');
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
        $appointment = Appointment::find($id);
        $date = date('d/M/Y', strtotime($appointment->appointment_date));
        $params = $this->settings; $today = Carbon::today();
        $start = number_format(date('H', strtotime($params->from_time)), 0);
        $end = number_format(date('H', strtotime($params->to_time)), 0);
        //$start = ($date > $today) ? $start : number_format(date('H'), 0);
        $doctors = $this->doctors; $branches = $this->branches;
        return view('appointment.edit', compact('appointment', 'doctors', 'branches', 'start', 'end', 'params'));
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
            'patient_name' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'mobile_number' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'branch' => 'required',
            'doctor' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        $input['appointment_date'] = (!empty($request->appointment_date)) ? Carbon::createFromFormat('d/M/Y', $request->appointment_date)->format('Y-m-d') : NULL;
        $input['appointment_time'] = ($request->status == 1) ? Carbon::createFromFormat('h:i A', $request->appointment_time)->format('H:i:s') : NULL;
        try{
            $apo = Appointment::find($id);
            $apo->update($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('appointment.index')->with('success','Appointment updated successfully');
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
