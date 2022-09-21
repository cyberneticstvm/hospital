<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
}
