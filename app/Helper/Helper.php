<?php

namespace App\Helper;
use App\Models\Procedure;
use App\Models\PatientMedicalRecord;
use App\Models\PatientReference;
use App\Models\Appointment;
use App\Models\InhouseCamp;
use App\Models\InhouseCampProcedure;
use Carbon\Carbon;
use DB;

class Helper{
    public static function getAvailableStock($product, $batch, $from_branch){
        if($from_branch == 0):
            $total_purchase = DB::table('purchases')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
        else:
            $total_purchase = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('to_branch', '=', $from_branch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('from_branch', '=', $from_branch)->sum('qty');
        endif;
        return $total_purchase - $total_transfer;
    }

    public static function sendSms($sms){
        $curl = curl_init();
		$data_string = json_encode($sms);
		$ch = curl_init('https://www.bulksmsplans.com/api/send_sms');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		$res = json_decode($result, true);
		//return ($res['code'] == 200) ? 200 : $res['code'];
        return $res;
    }

    public static function getProcedureFee($medical_record_id, $procedure){
        $proc = Procedure::find($procedure);
        $fee = $proc->fee;
        $mrecord = PatientMedicalRecord::find($medical_record_id);
        $pref = PatientReference::find($mrecord->mrn);
        if($pref->appointment_id > 0):
            $appointment = Appointment::find($pref->appointment_id);
            if($appointment->camp_id > 0):
                $camp = InhouseCamp::find($appointment->camp_id);
                $valid_to = Carbon::parse($appointment->appointment_date)->addDays($camp->validity)->format('Y-m-d');                
                $camps = InhouseCampProcedure::where('camp_id', $camp->id)->pluck('procedure')->all();
                $fee = (in_array($procedure, $camps) && $valid_to >= Carbon::today()) ? 0 : $proc->fee;
            endif;
        endif;
        return $fee;
    }
}

?>