<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\InhouseCamp;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch, $doctors, $branches, $settings, $camps;

    function __construct(){
        $this->middleware('permission:appointment-list|appointment-create|appointment-edit|appointment-delete|appointment-active-list', ['only' => ['index','store']]);
        $this->middleware('permission:appointment-create', ['only' => ['create','store']]);
        $this->middleware('permission:appointment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:appointment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:appointment-active-list', ['only' => ['activelist']]);

        $this->branch = session()->get('branch');
        $this->doctors = DB::table('doctors')->get();
        $this->branches = DB::table('branches')->get();
        $this->settings = DB::table('settings')->selectRaw("TIME_FORMAT(appointment_from_time, '%h:%i %p') AS from_time, TIME_FORMAT(appointment_to_time, '%h:%i %p') AS to_time, appointment_interval AS ti")->where('id', 1)->first();
        $this->camps = InhouseCamp::where('status', 1)->get();
    }
    public function index()
    {
        $appointments = Appointment::leftJoin('doctors as d', 'd.id', '=', 'appointments.doctor')->select('appointments.*', DB::RAW("DATE_FORMAT(appointments.appointment_date, '%d/%b/%Y') AS adate"), 'd.doctor_name')->where('appointments.branch', $this->branch)->where('appointments.appointment_date', '=', Carbon::today())->where('appointments.status', 1)->where('appointments.medical_record_id', 0)->orderByDesc('appointments.appointment_date')->get();        
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
        $doctors = $this->doctors; $branches = $this->branches; $camps = $this->camps;
        return view('appointment.create', compact('patient', 'doctors', 'branches', 'camps'));
    }

    public function gettime($date, $branch, $doctor){
        $params = $this->settings; $today = Carbon::today(); $op = "<option value=''>Select</option>";
        $start = number_format(date('H', strtotime($params->from_time)), 0);
        $end = number_format(date('H', strtotime($params->to_time)), 0);
        $start = ($date > $today) ? $start : number_format(date('H', strtotime('+1 hours')), 0);
        for($i=$start; $i<=$end; $i++):
            for($j=0; $j<=60-$params->ti; $j+=$params->ti):
                $val = $i.':'.$j; $val = date("h:i A", strtotime($val));
                $time = Carbon::parse($val)->toTimeString();
                $dis = DB::table('appointments')->selectRaw("TIME_FORMAT(appointment_time, '%h:%i %p') AS btime")->where('branch', $branch)->where('doctor', $doctor)->whereDate('appointment_date', $date)->whereTime('appointment_time', $time)->where('status', 1)->where('medical_record_id', 0)->first();
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
        $br = Branch::find($request->branch); $var = $request->appointment_time.'-'.$br->short_name;
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        $input['appointment_date'] = (!empty($request->appointment_date)) ? Carbon::createFromFormat('d/M/Y', $request->appointment_date)->format('Y-m-d') : NULL;
        $input['appointment_time'] = Carbon::createFromFormat('h:i A', $request->appointment_time)->format('H:i:s');
        Config::set('myconfig.sms.number', $request->mobile_number);
        Config::set('myconfig.sms.message', "Dear ".$request->patient_name.", Your appointment has been scheduled on ".$request->appointment_date." ".$var.", for enquiry please Call 9995050149. Thank You, Devi Eye Hospital. ");
        try{
            $rcount = Appointment::where('branch', $request->branch)->whereDate('appointment_date', $input['appointment_date'])->count('id');
            if($request->camp_id > 0):
                if($rcount >= $br->inhouse_camp_limit):
                    return redirect()->back()->with('error','Daily patient registration exceeded for provided date/branch/camp.')->withInput();
                endif;
            endif;
            $apo = Appointment::create($input);
            $code = Helper::sendSms(Config::get('myconfig.sms'));
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
            $doctors = $this->doctors; $branches = $this->branches; $camps = $this->camps;
            return view('appointment.create', compact('patient', 'doctors', 'branches', 'camps'));
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
        $camps = $this->camps;
        $date = date('d/M/Y', strtotime($appointment->appointment_date));
        $params = $this->settings; $today = Carbon::today();
        $start = number_format(date('H', strtotime($params->from_time)), 0);
        $end = number_format(date('H', strtotime($params->to_time)), 0);
        //$start = ($date > $today) ? $start : number_format(date('H'), 0);
        $doctors = $this->doctors; $branches = $this->branches;
        return view('appointment.edit', compact('appointment', 'doctors', 'branches', 'start', 'end', 'params', 'camps'));
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
        $br = Branch::find($request->branch);
        $input['updated_by'] = $request->user()->id;
        $input['appointment_date'] = (!empty($request->appointment_date)) ? Carbon::createFromFormat('d/M/Y', $request->appointment_date)->format('Y-m-d') : NULL;
        $input['appointment_time'] = ($request->status == 1) ? Carbon::createFromFormat('h:i A', $request->appointment_time)->format('H:i:s') : NULL;
        try{
            $rcount = Appointment::where('branch', $request->branch)->whereDate('appointment_date', $input['appointment_date'])->where('id', '!=', $id)->count('id');
            if($request->camp_id > 0):
                if($rcount >= $br->inhouse_camp_limit):
                    return redirect()->back()->with('error','Daily patient registration exceeded for provided date/branch/camp.')->withInput();
                endif;
            endif;
            $apo = Appointment::find($id);
            $apo->update($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('appointment.index')->with('success','Appointment updated successfully');
    }

    public function createPatient($id){
        $patient = Appointment::find($id);
        $cities = DB::table('city')->get();   
        $states = DB::table('state')->get();    
        $countries = DB::table('country')->get();    
        return view('patient.create', compact('cities', 'states', 'countries', 'patient'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ascan = Appointment::find($id)->delete();
        return redirect()->route('appointment.index')
                        ->with('success','Record deleted successfully');
    }

    public function activelist(){
        $appointments = Appointment::leftJoin('doctors as d', 'd.id', '=', 'appointments.doctor')->leftJoin('branches as b', 'b.id', '=', 'appointments.branch')->leftJoin('users as u', 'u.id', '=', 'appointments.created_by')->select('appointments.*', DB::raw("DATE_FORMAT(appointments.appointment_date, '%d/%b/%Y') AS adate"), 'b.branch_name', 'd.doctor_name', DB::raw("TIME_FORMAT(appointment_time, '%h:%i %p') AS atime"), 'u.name AS uname')->where('appointments.status', 1)->where('appointments.medical_record_id', 0)->orderByDesc('appointments.appointment_date')->get();
        return view('appointment.list', compact('appointments'));
    }
    public function listdestroy($id)
    {
        $ascan = Appointment::find($id)->delete();
        return redirect()->route('appointment.list')
                        ->with('success','Record deleted successfully');
    }

    public function check(){
        $app = Appointment::where('notification', 'N')->get();
        if($app->isNotEmpty()):            
            Appointment::where('notification', 'N')->update(['notification' => 'Y']);
            echo '1';
        endif;
    }
}
