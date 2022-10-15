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
    protected $id;
    function __construct(){
        $this->middleware('permission:settings-consultation-show|settings-consultation-update', ['only' => ['showConsultation','updateConsultation']]);
        $this->middleware('permission:settings-consultation-show', ['only' => ['showConsultation']]);
        $this->middleware('permission:settings-consultation-update', ['only' => ['updateConsultation']]);

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
        $password = Hash::make($request->password);        
        try{
            User::where('id', Auth::user()->id)->update(['password' => $password]);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('settings.showpassword')
                        ->with('success', "Password has been changed successfully.");
    }
}
