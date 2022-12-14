<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientReference as PRef;
use App\Models\LabClinic;
use QrCode;
use PDF;
use DB;

class PDFController extends Controller
{

    public function token($id){
        $data = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pr.id', $id)->select('pr.id', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at')->first();
        $patient = DB::table('patient_registrations')->find($data->patient_id);     
        $doctor = DB::table('doctors')->find($data->doctor_id);
        $branch = DB::table('branches')->find($data->branch);     
        //view()->share('patient', $data);     
        $pdf = PDF::loadView('/pdf/token', ['reference' => $data, 'patient' => $patient, 'doctor' => $doctor, 'branch' => $branch]);    
        //return $pdf->download('token.pdf');
        return $pdf->stream('token.pdf', array("Attachment"=>0));
    }

    public function prescription($id){
        $reference = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pr.id', $id)->select('pr.id', 'pr.consultation_type', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at')->first();
        $patient = DB::table('patient_registrations')->find($reference->patient_id);     
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));     
        //view()->share('patient', $reference);     
        $pdf = PDF::loadView('/pdf/prescription', compact('reference', 'patient', 'doctor', 'qrcode'));    
        //return $pdf->download('token.pdf');
        if($reference->consultation_type == 4):
            $pdf->output();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->set_opacity(.2,"Multiply");
            $canvas->set_opacity(.2);
            //$canvas->page_text($x, $y, $text, $font, 40,$color = array(255,0,0),$word_space = 0.0, $char_space = 0.0, $angle = 20.0);
            $canvas->page_text($width/2.5, $height/2, 'CAMP', null, 40, array(0,0,0),2,2,-40);
        endif;
        return $pdf->stream('prescription.pdf', array("Attachment"=>0));
    }

    public function receipt($id){
        $reference = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pr.id', $id)->select('pr.id', 'pr.doctor_fee', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at')->first();
        $procedure = DB::table('patient_procedures as pr')->where('medical_record_id', $id)->leftJoin('procedures as p', 'p.id', '=', 'pr.procedure')->select(DB::raw("IFNULL(GROUP_CONCAT(p.name), 'Na') AS procs, IFNULL(SUM(p.fee), 0.00) AS fee"))->first();
        $patient = DB::table('patient_registrations')->find($reference->patient_id);     
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/receipt', compact('reference', 'patient', 'doctor', 'qrcode', 'branch', 'procedure'));    
        return $pdf->stream('receipt.pdf', array("Attachment"=>0));
    }
    public function certreceipt($id){
        $cert = DB::table('patient_certificates')->find($id);
        $cert_details = DB::table('patient_certificate_details as c')->leftJoin('certificate_types as t', 'c.certificate_type', '=', 't.id')->select('t.name', 'c.fee')->where('patient_certificate_id', $id)->where('status', 'I')->get();
        $reference = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pmr.id', $cert->medical_record_id)->select('pr.id', 'pr.doctor_fee', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at')->first();
        $patient = DB::table('patient_registrations')->find($reference->patient_id);     
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/certificate-receipt', compact('reference', 'patient', 'doctor', 'qrcode', 'branch', 'cert_details'));    
        return $pdf->stream('receipt.pdf', array("Attachment"=>0));
    }

    public function pharmacybill($id){
        $medical_record = DB::table('patient_medical_records')->find($id);
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->select('p.product_name', 'p.hsn', 'm.batch_number', 'm.qty', 'm.price', 'm.tax_percentage', 'm.total', 'pd.expiry_date', DB::raw("CASE WHEN m.eye = 'R' THEN 'RE' WHEN m.eye='L' THEN 'LE' WHEN m.eye='B' THEN 'Both Eyes' ELSE 'Oral' END AS eye"))->leftJoin('purchase_details as pd', function($join){
            $join->on('pd.product', '=', 'm.medicine')->on('pd.batch_number', '=', 'm.batch_number');
        })->where('m.medical_record_id', $id)->groupBy('p.product_name', 'p.hsn', 'm.batch_number', 'm.qty', 'm.price', 'm.tax_percentage', 'm.total', 'pd.expiry_date')->get();
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id);     
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/pharmacy-bill', compact('patient', 'doctor', 'qrcode', 'branch', 'medical_record', 'medicines'));    
        return $pdf->stream('pharmacy-bill.pdf', array("Attachment"=>0));
    }

    public function pharmacyout($id){
        $medical_record = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id); 
        $branch = DB::table('branches')->find($patient->branch);
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.product_name', 'm.qty', 'm.dosage', 'm.duration', 'm.notes', 't.name', DB::raw("CASE WHEN m.eye='L' THEN 'Left Eye Only' WHEN m.eye='R' THEN 'Right Eye Only' WHEN m.eye='B' THEN 'Both Eyes' ELSE 'Oral' END AS eye"))->where('m.medical_record_id', $id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/pharmacy-out', compact('patient', 'doctor', 'qrcode', 'branch', 'medical_record', 'medicines'));    
        return $pdf->stream('prescription.pdf', array("Attachment"=>0));
    }

    public function medicalrecord($id){
        $record = DB::table('patient_medical_records')->find($id);
        $retina_od = DB::table('patient_medical_records_retina')->select('retina_img', 'description')->where('medical_record_id', $id)->where('retina_type', 'od')->get()->toArray();
        $retina_os = DB::table('patient_medical_records_retina')->select('retina_img', 'description')->where('medical_record_id', $id)->where('retina_type', 'os')->get()->toArray();
        $v_od_1 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img1')->where('medical_record_id', $id)->value('names');
        $v_os_1 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img1')->where('medical_record_id', $id)->value('names');
        $v_od_2 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img2')->where('medical_record_id', $id)->value('names');
        $v_os_2 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img2')->where('medical_record_id', $id)->value('names');
        $v_od_3 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img3')->where('medical_record_id', $id)->value('names');
        $v_os_3 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img3')->where('medical_record_id', $id)->value('names');
        $v_od_4 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img4')->where('medical_record_id', $id)->value('names');
        $v_os_4 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img4')->where('medical_record_id', $id)->value('names');
        //$medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->select('p.product_name', 'm.dosage', 'm.notes', 'm.qty', DB::raw("CASE WHEN m.eye = 'R' THEN 'RE' WHEN m.eye='L' THEN 'LE' ELSE 'Both' END AS eye"))->where('m.medical_record_id', $id)->get();
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.product_name', 'm.qty', 'm.dosage', 'm.duration', 'm.notes', 't.name', DB::raw("CASE WHEN m.eye='L' THEN 'Left Eye Only' WHEN m.eye='R' THEN 'Right Eye Only' WHEN m.eye='B' THEN 'Both Eyes' ELSE 'Oral' END AS eye"))->where('m.medical_record_id', $id)->get();
        $patient = DB::table('patient_registrations')->find($record->patient_id);
        $reference = DB::table('patient_references')->find($record->mrn);     
        $doctor = DB::table('doctors')->find($record->doctor_id);
        $branch = DB::table('branches')->find($reference->branch);
        $sympt = explode(',', $record->symptoms);
        $diag = explode(',', $record->diagnosis);
        $symptoms = DB::table('symptoms')->whereIn('id', $sympt)->get();
        $diagnosis = DB::table('diagnosis')->whereIn('id', $diag)->get();
        $spectacle = DB::table('spectacles')->where('medical_record_id', $id)->first();
        $tonometry = DB::table('tonometries')->where('medical_record_id', $id)->first();
        $keratometry = DB::table('keratometries')->where('medical_record_id', $id)->first();
        $ascan = DB::table('ascans')->where('medical_record_id', $id)->first();
        $onotes = DB::table('operation_notes')->where('medical_record_id', $id)->first();
        $pachymetry = DB::table('pachymetries')->where('medical_record_id', $id)->first();

        $sel_1_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_1_od))->value('names');
        $sel_1_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_1_os))->value('names');
        $sel_2_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_2_od))->value('names');
        $sel_2_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_2_os))->value('names');
        $sel_3_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_3_od))->value('names');
        $sel_3_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_3_os))->value('names');
        $sel_4_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_4_od))->value('names');
        $sel_4_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_4_os))->value('names');
        $sel_5_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_5_od))->value('names');
        $sel_5_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_5_os))->value('names');
        $sel_6_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_6_od))->value('names');
        $sel_6_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_6_os))->value('names');
        $sel_7_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_7_od))->value('names');
        $sel_7_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_7_os))->value('names');
        $sel_8_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_8_od))->value('names');
        $sel_8_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_8_os))->value('names');
        $sel_9_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_9_od))->value('names');
        $sel_9_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_9_os))->value('names');
        $sel_10_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_10_od))->value('names');
        $sel_10_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_10_os))->value('names');
        $sel_11_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_11_od))->value('names');
        $sel_11_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_11_os))->value('names');
        $sel_12_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_12_od))->value('names');
        $sel_12_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_12_os))->value('names');
        $sel_13_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_13_od))->value('names');
        $sel_13_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_13_os))->value('names');
        $sel_14_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_14_od))->value('names');
        $sel_14_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_14_os))->value('names');
        $sel_15_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_15_od))->value('names');
        $sel_15_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_15_os))->value('names');
        $sel_16_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_16_od))->value('names');
        $sel_16_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_16_os))->value('names');
        $sel_17_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_17_od))->value('names');
        $sel_17_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_17_os))->value('names');
        $sel_18_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_18_od))->value('names');
        $sel_18_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_18_os))->value('names');
        $sel_19_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_19_od))->value('names');
        $sel_19_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_19_os))->value('names');
        $sel_20_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_20_od))->value('names');
        $sel_20_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $record->sel_20_os))->value('names');
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/medical-record', compact('record', 'patient', 'doctor', 'qrcode', 'branch', 'reference', 'symptoms', 'diagnosis', 'medicines', 'spectacle', 'tonometry', 'keratometry', 'ascan', 'pachymetry', 'onotes', 'retina_od', 'retina_os', 'v_od_1', 'v_os_1', 'v_od_2', 'v_os_2', 'v_od_3', 'v_os_3', 'v_od_4', 'v_os_4', 'sel_1_od', 'sel_1_os', 'sel_2_od', 'sel_2_os', 'sel_3_od', 'sel_3_os', 'sel_4_od', 'sel_4_os', 'sel_5_od', 'sel_5_os', 'sel_6_od', 'sel_6_os', 'sel_7_od', 'sel_7_os', 'sel_8_od', 'sel_8_os', 'sel_9_od', 'sel_9_os', 'sel_10_od', 'sel_10_os', 'sel_11_od', 'sel_11_os', 'sel_12_od', 'sel_12_os', 'sel_13_od', 'sel_13_os', 'sel_14_od', 'sel_14_os', 'sel_15_od', 'sel_15_os', 'sel_16_od', 'sel_16_os', 'sel_17_od', 'sel_17_os', 'sel_18_od', 'sel_18_os', 'sel_19_od', 'sel_19_os', 'sel_20_od', 'sel_20_os'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function medicalrecordhistory($id){
        $patient = DB::table('patient_registrations')->find($id);
        $references = DB::table('patient_references')->where('patient_id', $id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/medical-record-history', compact('patient', 'qrcode', 'references'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function spectacleprescription($id){
        $spectacle = DB::table('spectacles')->find($id);
        $mrecord = DB::table('patient_medical_records')->find($spectacle->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/spectacle-prescription', compact('patient', 'qrcode', 'reference', 'spectacle', 'mrecord', 'doctor', 'branch'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function radiologyprescription($id){
        $labs = DB::table('lab_radiologies')->where('medical_record_id', $id)->where('tested_from', 0)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-radiology-prescription', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function radiologybill($id){
        $bno = DB::table('lab_radiologies')->where('medical_record_id', $id)->select('bill_number')->first();
        $labs = DB::table('lab_radiologies')->where('medical_record_id', $id)->where('tested_from', 1)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-radiology-bill', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch', 'bno'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function radiologyreport($id){
        $labs = DB::table('lab_radiologies')->where('medical_record_id', $id)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-radiology-report', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function clinicprescription($id){
        $labs = DB::table('lab_clinics')->where('medical_record_id', $id)->where('tested_from', 0)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-clinic-prescription', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function clinicbill($id){
        $bno = DB::table('lab_clinics')->where('medical_record_id', $id)->select('bill_number')->first();
        $labs = DB::table('lab_clinics')->where('medical_record_id', $id)->where('tested_from', 1)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-clinic-bill', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch', 'bno'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function clinicreport($id){
        $labs = DB::table('lab_clinics')->where('medical_record_id', $id)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-clinic-report', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function pharmacyreceipt($id){
        $record = DB::table('pharmacies')->find($id);
        $branch = DB::table('branches')->find($record->branch);
        $medicines = DB::table('pharmacy_records as pr')->leftJoin('products as p', 'pr.product', '=', 'p.id')->leftJoin('medicine_types as m', 'm.id', '=', 'pr.type')->select('p.product_name', 'pr.qty', 'pr.batch_number', 'pr.price', 'pr.discount', 'pr.tax', 'pr.tax_amount', 'pr.total', 'm.name as type')->where('pr.pharmacy_id', $id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/pharmacy-receipt', compact('record', 'medicines', 'qrcode', 'branch'));    
        return $pdf->stream('receipt.pdf', array("Attachment"=>0));
    }
    public function patienthistory($id){
        $mrecords = DB::table('patient_medical_records')->where('patient_id', $id)->get();
        $patient = DB::table('patient_registrations')->where('id', $id)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/patient-history', compact('mrecords', 'qrcode', 'patient'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }
    public function visioncertificate($id){
        $patient = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->select('pr.patient_name', 'pr.age')->where('pmr.id', $id)->first();        
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://hospital.speczone.net/auth/certificate/$id/"));         
        $pdf = PDF::loadView('/pdf/license/vision', compact('qrcode', 'patient'));    
        return $pdf->stream('vision.pdf', array("Attachment"=>0));
    }
    public function medicalcertificate($id){
        $patient = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->select('pr.patient_name', 'pr.age')->where('pmr.id', $id)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://hospital.speczone.net/auth/certificate/$id/"));         
        $pdf = PDF::loadView('/pdf/license/medical', compact('qrcode', 'patient'));    
        return $pdf->stream('medical.pdf', array("Attachment"=>0));
    }
    public function campprint($id){
        $camp = DB::table('camps')->find($id);
        $campm = DB::table('camp_masters')->find($camp->camp_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/camp', compact('qrcode', 'camp', 'campm'));    
        return $pdf->stream('camp.pdf', array("Attachment"=>0));
    }
    public function campmasterprint($id){
        $campm = DB::table('camp_masters')->find($id);
        $camps = DB::table('camps')->where('camp_id', $id)->get();
        $branch = DB::table('branches')->find($campm->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/campmaster', compact('qrcode', 'camps', 'campm', 'branch'));
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();    
        $canvas->page_text($x = 50, $y = 800, $text = "Page {PAGE_NUM} of {PAGE_COUNT}", $font = null, $size = 10, $color = array(0,0,0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0);
        $canvas->page_text($x = 450, $y = 800, $text = $campm->camp_id, $font = null, $size = 10, $color = array(0,0,0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0);
        return $pdf->stream('camp.pdf', array("Attachment"=>0));
    }
    public function tonometryreceipt($id){
        $tonometry = DB::table('tonometries')->find($id);
        $patient = DB::table('patient_registrations')->find($tonometry->patient_id);
        $branch = DB::table('branches')->find($tonometry->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'p.fee')->where('pp.medical_record_id', $tonometry->medical_record_id)->where('pp.type', 'T')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/tonometry/receipt', compact('qrcode', 'tonometry', 'patient', 'branch', 'procedures'));    
        return $pdf->stream('tonometry.pdf', array("Attachment"=>0));
    }
    public function tonometryreport($id){
        $tonometry = DB::table('tonometries')->find($id);
        $patient = DB::table('patient_registrations')->find($tonometry->patient_id);
        $branch = DB::table('branches')->find($tonometry->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/tonometry/report', compact('qrcode', 'tonometry', 'patient', 'branch'));    
        return $pdf->stream('tonometry.pdf', array("Attachment"=>0));
    }
    public function keratometryreceipt($id){
        $keratometry = DB::table('keratometries')->find($id);
        $patient = DB::table('patient_registrations')->find($keratometry->patient_id);
        $branch = DB::table('branches')->find($keratometry->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'p.fee')->where('pp.medical_record_id', $keratometry->medical_record_id)->where('pp.type', 'K')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/keratometry/receipt', compact('qrcode', 'keratometry', 'patient', 'branch', 'procedures'));    
        return $pdf->stream('tonometry.pdf', array("Attachment"=>0));
    }
    public function keratometryreport($id){
        $keratometry = DB::table('keratometries')->find($id);
        $patient = DB::table('patient_registrations')->find($keratometry->patient_id);
        $branch = DB::table('branches')->find($keratometry->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/keratometry/report', compact('qrcode', 'keratometry', 'patient', 'branch'));    
        return $pdf->stream('tonometry.pdf', array("Attachment"=>0));
    }
    public function ascanreceipt($id){
        $ascan = DB::table('ascans')->find($id);
        $patient = DB::table('patient_registrations')->find($ascan->patient_id);
        $branch = DB::table('branches')->find($ascan->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'p.fee')->where('pp.medical_record_id', $ascan->medical_record_id)->where('pp.type', 'A')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/ascan/receipt', compact('qrcode', 'ascan', 'patient', 'branch', 'procedures'));    
        return $pdf->stream('tonometry.pdf', array("Attachment"=>0));
    }
    public function ascanreport($id){
        $ascan = DB::table('ascans')->find($id);
        $keratometry = DB::table('keratometries')->where('medical_record_id', $ascan->medical_record_id)->first();
        $patient = DB::table('patient_registrations')->find($ascan->patient_id);
        $branch = DB::table('branches')->find($ascan->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/ascan/report', compact('qrcode', 'keratometry', 'patient', 'branch', 'ascan'));    
        return $pdf->stream('tonometry.pdf', array("Attachment"=>0));
    }
    public function visionreceipt($id){
        $spectacle = DB::table('spectacles')->find($id);
        $pref = DB::table('patient_references')->find($spectacle->medical_record_id);
        $patient = DB::table('patient_registrations')->find($pref->patient_id);
        $branch = DB::table('branches')->find($pref->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/vision-receipt', compact('qrcode', 'spectacle', 'patient', 'branch', 'pref'));    
        return $pdf->stream('vision-receipt.pdf', array("Attachment"=>0));
    }
    public function printletterhead($id){
        $matter = DB::table('letter_heads')->find($id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/letterhead', compact('qrcode', 'matter'));    
        return $pdf->stream('letterhead.pdf', array("Attachment"=>0));
    }
    public function printmfit($id){
        $mfit = DB::table('medical_fitnesses')->find($id);
        $madvice = DB::table('surgery_types')->find($mfit->fitness_advice);
        $patient = DB::table('patient_registrations')->find($mfit->patient);
        $pref = DB::table('patient_references')->find($mfit->medical_record_id);
        $branch = DB::table('branches')->find($mfit->branch);
        $doctor = DB::table('doctors')->find($pref->doctor_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/medicalfitness', compact('qrcode', 'mfit', 'patient', 'pref', 'branch', 'doctor', 'madvice'));    
        return $pdf->stream('letterhead.pdf', array("Attachment"=>0));
    }
    public function pachymetryreceipt($id){
        $pachymetry = DB::table('pachymetries')->find($id);
        $patient = DB::table('patient_registrations')->find($pachymetry->patient_id);
        $branch = DB::table('branches')->find($pachymetry->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'p.fee')->where('pp.medical_record_id', $pachymetry->medical_record_id)->where('pp.type', 'P')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/pachymetry/receipt', compact('qrcode', 'pachymetry', 'patient', 'branch', 'procedures'));    
        return $pdf->stream('pachymetry.pdf', array("Attachment"=>0));
    }
    public function pachymetryreport($id){
        $pachymetry = DB::table('pachymetries')->find($id);
        $patient = DB::table('patient_registrations')->find($pachymetry->patient_id);
        $branch = DB::table('branches')->find($pachymetry->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));         
        $pdf = PDF::loadView('/pdf/pachymetry/report', compact('qrcode', 'pachymetry', 'patient', 'branch'));    
        return $pdf->stream('pachymetry.pdf', array("Attachment"=>0));
    }
    public function purchasebill($id){
        $purchase = DB::table('purchases')->find($id);
        $purchases = DB::table('purchase_details as pd')->leftJoin('products as p', 'p.id', '=', 'pd.product')->selectRaw("pd.*, p.product_name")->where('purchase_id', $purchase->id)->get();
        $tot = $purchases->sum('total');
        $supplier = DB::table('suppliers')->find($purchase->supplier);
        $pdf = PDF::loadView('/pdf/purchase-bill', compact('purchase', 'purchases', 'supplier', 'tot'));    
        return $pdf->stream('invoice.pdf', array("Attachment"=>0));
    }
}
