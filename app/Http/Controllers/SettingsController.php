<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use DB;

class SettingsController extends Controller
{
    protected $id, $branch;
    function __construct(){
        $this->middleware('permission:settings-consultation-show|settings-consultation-update|settings-closing-balance-show|settings-closing-balance-update', ['only' => ['showConsultation','updateConsultation', 'fetchClosingBalanceforUpdate', 'updateClosingBalance']]);
        $this->middleware('permission:settings-consultation-show', ['only' => ['showConsultation']]);
        $this->middleware('permission:settings-consultation-update', ['only' => ['updateConsultation']]);

        $this->middleware('permission:settings-closing-balance-show', ['only' => ['fetchClosingBalanceforUpdate']]);
        $this->middleware('permission:settings-closing-balance-update', ['only' => ['updateClosingBalance']]);

        $this->middleware('permission:settings-appointment-show', ['only' => ['showAppointment']]);
        $this->middleware('permission:settings-appointment-update', ['only' => ['updateAppointment']]);

        $this->branch = session()->get('branch');
        $this->id = 1;
    }
    public function showConsultation(){
        $settings = DB::table('settings')->find($this->id);
        return view('settings.consultation', compact('settings'));
    }
    public function updateConsultation(Request $request){
        $settings = DB::table('settings')->find($this->id);
        $this->validate($request, [
            'consultation_fee_days' => 'required',
        ]);
        $consultation = DB::table('settings')->where('id', $this->id)->update(['consultation_fee_days' => $request->consultation_fee_days]);
        return redirect()->route('settings.showconsultation', compact('settings'))
                        ->with('success','Settings updated successfully');
    }
    public function showpassword(){
        return view('settings.change-password');
    }
    public function updatePassword(Request $request){
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);                
        try{
            $password = Hash::make($request->password);
            User::where('id', Auth::user()->id)->update(['password' => $password]);
        }catch(Exception $e){
            //throw $e;
        }
        return redirect()->route('settings.showpassword')
                        ->with('success', "Password has been changed successfully.");
    }
    public function fetchclosingbalance(){
        $inputs = []; $record = [];
        return view('settings.revise-closing-balance', compact('inputs', 'record'));
    }
    public function fetchClosingBalanceforUpdate(Request $request){
        $this->validate($request, [
            'date' => 'required',
        ]); 
        $inputs = array($request->date);
        $date = Carbon::createFromFormat('d/M/Y', $request->date)->format('Y-m-d');
        $record = DB::table('daily_closing')->whereDate('date', $date)->where('branch', $this->branch)->first();
        return view('settings.revise-closing-balance', compact('inputs', 'record'));
    }
    public function updateClosingBalance(Request $request){
        $this->validate($request, [
            'ddate' => 'required',
            'amount' => 'required',
            'operand' => 'required',
        ]); 
        $date = Carbon::createFromFormat('d/M/Y', $request->ddate)->format('Y-m-d');
        if($request->operand == 'add'):
            DB::table("daily_closing")->where("branch", $this->branch)->where('date', '>=', $date)->increment('closing_balance', $request->amount);
        else:
            DB::table("daily_closing")->where("branch", $this->branch)->where('date', '>=', $date)->decrement('closing_balance', $request->amount);
        endif;
        return redirect()->back()->with('message', 'Record updated successfully');
    }
    public function showAppointment(){
        $start = strtotime("00:00"); $end = strtotime("23:30");
        $settings = DB::table('settings')->selectRaw("DATE_FORMAT(appointment_from_time, '%h:%i %p') AS appointment_from_time, DATE_FORMAT(appointment_to_time, '%h:%i %p') AS appointment_to_time, appointment_interval")->find($this->id);
        return view('settings.appointment', compact('settings', 'start', 'end'));
    }
    public function updateAppointment(Request $request){
        $settings = DB::table('settings')->find($this->id);
        $this->validate($request, [
            'appointment_from_time' => 'required',
            'appointment_to_time' => 'required',
            'appointment_interval' => 'required',
        ]);
        $from = Carbon::createFromFormat('h:i A', $request->appointment_from_time)->format('H:i:s');
        $to = Carbon::createFromFormat('h:i A', $request->appointment_to_time)->format('H:i:s');
        $consultation = DB::table('settings')->where('id', $this->id)->update(['appointment_from_time' => $from, 'appointment_to_time' => $to, 'appointment_interval' => $request->appointment_interval]);
        return redirect()->route('settings.showappointment', compact('settings'))
                        ->with('success','Settings updated successfully');
    }
}
