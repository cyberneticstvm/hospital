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
        if($pref->camp_id > 0):
            $camp = InhouseCamp::find($pref->camp_id);              
            $valid_to = Carbon::parse($pref->created_at)->addDays($camp->validity)->format('Y-m-d');       
            $camps = InhouseCampProcedure::where('camp_id', $camp->id)->pluck('procedure')->all();
            $fee = (in_array($procedure, $camps) && $valid_to >= Carbon::today()) ? 0 : $proc->fee;
        endif;
        return $fee;
    }

    public function getClosingBalance($branch){

        $prev_day = Carbon::today()->subDays(1);
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $opening_balance = DB::table('daily_closing as d')->select(DB::raw("MAX(d.id), IFNULL(d.closing_balance, 0) AS closing_balance"))->whereDate('d.date', '=', $prev_day)->where('d.branch', $branch)->orderByDesc('d.id')->first()->closing_balance;

        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pr.branch', $branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->whereBetween('pr.created_at', [$startDate, $endDate])->where('pr.branch', $branch)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $branch)->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->whereBetween('pc.created_at', [$startDate, $endDate])->where('pc.branch_id', $branch)->where('pcd.status', 'I')->sum('pcd.fee');        

        $pharmacy = DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->where('m.branch', $branch)->whereBetween('s.created_at', [$startDate, $endDate])->sum('s.fee');

        $income = DB::table('incomes')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->where('type', '!=', 9)->sum('amount');

        $income_received_other = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [2,3,4,5,7])->where('type', '!=', 9)->sum('amount');

        $clinical_lab = DB::table('lab_clinics as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $branch)->sum('l.fee');

        $radiology_lab = DB::table('lab_radiologies as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $branch)->sum('l.fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $branch)->sum('d.total'); 

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $branch)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::whereBetween('created_at', [$startDate, $endDate])->where('branch', $branch)->sum('total_after_discount');

        $outstanding_received = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 9)->sum('amount');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $income + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;

        $closing_balance = $income_total-($income_received_other + $outstanding_received + $expense);

        return $closing_balance;
    }
}

?>