<?php

namespace App\Helper;

use App\Models\Procedure;
use App\Models\PatientMedicalRecord;
use App\Models\PatientReference;
use App\Models\PatientSurgeryConsumable;
use App\Models\InhouseCamp;
use App\Models\InhouseCampProcedure;
use Carbon\Carbon;
use DB;

class Helper
{
    public static function api_url()
    {
        return "https://order.speczone.net";
    }
    public static function apiSecret()
    {
        return 'fdjsvsgdf4dhgf687f4bg54g4hf787';
    }

    public static function getAvailableStock($product, $batch, $from_branch)
    {
        if ($from_branch == 0):
            $total_purchase = DB::table('purchases')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
        else:
            $total_purchase = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('to_branch', '=', $from_branch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('from_branch', '=', $from_branch)->sum('qty');
        endif;
        return $total_purchase - $total_transfer;
    }

    public static function sendSms($sms)
    {
        $curl = curl_init();
        $data_string = json_encode($sms);
        $ch = curl_init('https://www.bulksmsplans.com/api/send_sms');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        //return ($res['code'] == 200) ? 200 : $res['code'];
        return $res;
    }

    public static function getProcedureFee($medical_record_id, $procedure)
    {
        $proc = Procedure::find($procedure);
        $fee = $proc->fee;
        $mrecord = PatientMedicalRecord::find($medical_record_id);
        $pref = PatientReference::find($mrecord->mrn);
        if ($pref->camp_id > 0):
            $camp = InhouseCamp::find($pref->camp_id);
            $valid_to = Carbon::parse($pref->created_at)->addDays($camp->validity)->format('Y-m-d');
            $camps = InhouseCampProcedure::where('camp_id', $camp->id)->pluck('procedure')->all();
            $fee = (in_array($procedure, $camps) && $valid_to >= Carbon::today()) ? 0 : $proc->fee;
        endif;
        return $fee;
    }

    public static function getOwedTotal($mrid)
    {
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->where('pmr.id', $mrid)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $mrid)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.medical_record_id', $mrid)->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.medical_record_id', $mrid)->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('p.id', $mrid)->where('m.status', 1)->sum('m.total');

        $vision = DB::table('spectacles')->where('medical_record_id', $mrid)->sum('fee');

        $clinical_lab = DB::table('lab_clinics')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $radiology_lab = DB::table('lab_radiologies')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->where('m.medical_record_id', $mrid)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->where('m.medical_record_id', $mrid)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::where('medical_record_id', $mrid)->sum('total_after_discount');

        return $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $medicine + $vision + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;
    }

    public static function getPaidTotal($mrid)
    {
        $paid = DB::table('patient_payments as p')->where('p.medical_record_id', $mrid)->where('type', '!=', 8)->sum('amount');
        return $paid;
    }

    public static function getOwedTotalForStatement($mrid)
    {
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->where('pmr.id', $mrid)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $mrid)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.medical_record_id', $mrid)->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.medical_record_id', $mrid)->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('p.id', $mrid)->where('m.status', 1)->sum('m.total');

        $vision = DB::table('spectacles')->where('medical_record_id', $mrid)->sum('fee');

        $clinical_lab = DB::table('lab_clinics')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $radiology_lab = DB::table('lab_radiologies')->where('medical_record_id', $mrid)->where('tested_from', 1)->sum('fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->where('m.medical_record_id', $mrid)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->where('m.medical_record_id', $mrid)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::where('medical_record_id', $mrid)->sum('total_after_discount');

        return array('registration' => $reg_fee_total, 'consultation' => $consultation_fee_total, 'procedure' => $procedure_fee_total, 'certificate' => $certificate_fee_total, 'pharmacy' => $medicine, 'vision' => $vision, 'clinic' => $clinical_lab, 'radiology' => $radiology_lab, 'surgerymed' => $surgery_medicine, 'postop' => $postop_medicine, 'surgeryconsumable' => $surgery_consumables);
    }
}
