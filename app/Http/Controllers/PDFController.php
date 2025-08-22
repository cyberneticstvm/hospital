<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AxialLength;
use App\Models\Branch;
use App\Models\Diagnosis;
use App\Models\DischargeSummary;
use App\Models\DischargeSummaryDiagnosis;
use App\Models\doctor;
use App\Models\InhouseCamp;
use Illuminate\Http\Request;
use App\Models\PatientReference as PRef;
use App\Models\LabClinic;
use App\Models\OperationNote;
use App\Models\PatientAcknoledgement;
use App\Models\PatientAcknowledgementProcedure;
use App\Models\PatientMedicalRecord;
use App\Models\PatientProcedure;
use App\Models\PatientReference;
use App\Models\PatientRegistrations;
use App\Models\PatientSurgeryConsumable;
use App\Models\Procedure;
use App\Models\Spectacle;
use App\Models\Surgery;
use QrCode;
use PDF;
use DB;

class PDFController extends Controller
{

    public function token($id)
    {
        $data = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pr.id', $id)->select('pr.id', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at')->first();
        $patient = DB::table('patient_registrations')->find($data->patient_id);
        $doctor = DB::table('doctors')->find($data->doctor_id);
        $branch = DB::table('branches')->find($data->branch);
        //view()->share('patient', $data);     
        $pdf = PDF::loadView('/pdf/token', ['reference' => $data, 'patient' => $patient, 'doctor' => $doctor, 'branch' => $branch]);
        //return $pdf->download('token.pdf');
        return $pdf->stream('token.pdf', array("Attachment" => 0));
    }

    public function prescription($id)
    {
        $reference = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pr.id', $id)->select('pr.id', 'pr.rc_type', 'pr.rc_number', 'pr.consultation_type', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at', 'pr.appointment_id')->first();
        $patient = PatientRegistrations::find($reference->patient_id);
        $branch = Branch::findOrFail($reference->branch);
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->errorCorrection('H')->generate('https://play.google.com/store/apps/details?id=com.devieh.virtualtoken'));
        //view()->share('patient', $reference);     
        $pdf = PDF::loadView('/pdf/prescription', compact('reference', 'patient', 'doctor', 'qrcode', 'branch'));
        //return $pdf->download('token.pdf');
        if ($reference->consultation_type == 4 || $reference->rc_type && $reference->rc_number) :
            $txt = ($reference->consultation_type == 4) ? 'CAMP' : (($reference->consultation_type == 9) ? 'SURGERY' : 'ROYALTY CARD');
            if ($reference->appointment_id > 0) :
                $app = Appointment::find($reference->appointment_id);
                if ($app->camp_id > 0) :
                    $camp = InhouseCamp::find($app->camp_id);
                    $txt = strtoupper($camp?->name);
                endif;
            endif;
            $pdf->output();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->set_opacity(.2, "Multiply");
            $canvas->set_opacity(.2);
            //$canvas->page_text($x, $y, $text, $font, 40,$color = array(255,0,0),$word_space = 0.0, $char_space = 0.0, $angle = 20.0);
            $canvas->page_text($width / 2.5, $height / 2, $txt, null, 40, array(0, 0, 0), 2, 2, -40);
        endif;
        return $pdf->stream('prescription.pdf', array("Attachment" => 0));
    }

    public function receipt($id)
    {
        $reference = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pr.id', $id)->select('pr.id', 'pr.doctor_fee', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at', 'pr.review', 'pr.rc_type', 'pr.rc_number', 'pr.discount')->first();
        //$procedure = DB::table('patient_procedures as pr')->where('medical_record_id', $id)->leftJoin('procedures as p', 'p.id', '=', 'pr.procedure')->select(DB::raw("IFNULL(GROUP_CONCAT(p.name), 'Na') AS procs, IFNULL(SUM(pr.fee), 0.00) AS fee, IFNULL(SUM(pr.discount), 0.00) AS discount"))->first();
        $procedure = PatientProcedure::where('medical_record_id', $id)->get();
        $patient = DB::table('patient_registrations')->find($reference->patient_id);
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/receipt', compact('reference', 'patient', 'doctor', 'qrcode', 'branch', 'procedure'));
        return $pdf->stream('receipt.pdf', array("Attachment" => 0));
    }
    public function certreceipt($id)
    {
        $cert = DB::table('patient_certificates')->find($id);
        $cert_details = DB::table('patient_certificate_details as c')->leftJoin('certificate_types as t', 'c.certificate_type', '=', 't.id')->select('t.name', 'c.fee')->where('patient_certificate_id', $id)->where('status', 'I')->get();
        $reference = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->where('pmr.id', $cert->medical_record_id)->select('pr.id', 'pr.doctor_fee', 'pmr.id as medical_record_id', 'pr.token', 'pr.patient_id', 'pr.doctor_id', 'pr.branch', 'pr.created_at')->first();
        $patient = DB::table('patient_registrations')->find($reference->patient_id);
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/certificate-receipt', compact('reference', 'patient', 'doctor', 'qrcode', 'branch', 'cert_details'));
        return $pdf->stream('receipt.pdf', array("Attachment" => 0));
    }

    public function pharmacybill($id)
    {
        $medical_record = DB::table('patient_medical_records')->find($id);
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->select('p.product_name', 'p.hsn', 'm.batch_number', 'm.qty', 'm.price', 'm.tax_percentage', 'm.total', 'pd.expiry_date', DB::raw("CASE WHEN m.eye = 'R' THEN 'RE' WHEN m.eye='L' THEN 'LE' WHEN m.eye='B' THEN 'Both Eyes' ELSE 'Oral' END AS eye"))->leftJoin('purchase_details as pd', function ($join) {
            $join->on('pd.product', '=', 'm.medicine')->on('pd.batch_number', '=', 'm.batch_number');
        })->where('m.medical_record_id', $id)->groupBy('p.product_name', 'p.hsn', 'm.batch_number', 'm.qty', 'm.price', 'm.tax_percentage', 'm.total', 'pd.expiry_date')->get();
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id);
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/pharmacy-bill', compact('patient', 'doctor', 'qrcode', 'branch', 'medical_record', 'medicines'));
        return $pdf->stream('pharmacy-bill.pdf', array("Attachment" => 0));
    }

    public function pharmacyout($id)
    {
        $medical_record = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id);
        $branch = DB::table('branches')->find($patient->branch);
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.product_name', 'm.qty', 'm.dosage', 'm.duration', 'm.notes', 't.name', DB::raw("CASE WHEN m.eye='L' THEN 'Left Eye Only' WHEN m.eye='R' THEN 'Right Eye Only' WHEN m.eye='B' THEN 'Both Eyes' ELSE 'Oral' END AS eye"))->where('m.medical_record_id', $id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/pharmacy-out', compact('patient', 'doctor', 'qrcode', 'branch', 'medical_record', 'medicines'));
        return $pdf->stream('prescription.pdf', array("Attachment" => 0));
    }

    public function medicalrecord($id)
    {
        $id = (intval($id) > 0) ? $id : decrypt($id);
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
        $spectacle = Spectacle::where('medical_record_id', $id)->first();
        $tonometry = DB::table('tonometries')->where('medical_record_id', $id)->whereNull('deleted_at')->first();
        $keratometry = DB::table('keratometries')->where('medical_record_id', $id)->whereNull('deleted_at')->first();
        $ascan = DB::table('ascans')->where('medical_record_id', $id)->whereNull('deleted_at')->first();
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
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->errorCorrection('H')->generate('https://play.google.com/store/apps/details?id=com.devieh.virtualtoken'));
        $pdf = PDF::loadView('/pdf/medical-record', compact('record', 'patient', 'doctor', 'qrcode', 'branch', 'reference', 'symptoms', 'diagnosis', 'medicines', 'spectacle', 'tonometry', 'keratometry', 'ascan', 'pachymetry', 'onotes', 'retina_od', 'retina_os', 'v_od_1', 'v_os_1', 'v_od_2', 'v_os_2', 'v_od_3', 'v_os_3', 'v_od_4', 'v_os_4', 'sel_1_od', 'sel_1_os', 'sel_2_od', 'sel_2_os', 'sel_3_od', 'sel_3_os', 'sel_4_od', 'sel_4_os', 'sel_5_od', 'sel_5_os', 'sel_6_od', 'sel_6_os', 'sel_7_od', 'sel_7_os', 'sel_8_od', 'sel_8_os', 'sel_9_od', 'sel_9_os', 'sel_10_od', 'sel_10_os', 'sel_11_od', 'sel_11_os', 'sel_12_od', 'sel_12_os', 'sel_13_od', 'sel_13_os', 'sel_14_od', 'sel_14_os', 'sel_15_od', 'sel_15_os', 'sel_16_od', 'sel_16_os', 'sel_17_od', 'sel_17_os', 'sel_18_od', 'sel_18_os', 'sel_19_od', 'sel_19_os', 'sel_20_od', 'sel_20_os'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function medicalrecordhistory($id)
    {
        $id = (intval($id) > 0) ? $id : decrypt($id);
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $references = DB::table('patient_references')->where('patient_id', $mrecord->patient_id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/medical-record-history', compact('patient', 'qrcode', 'references'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function spectacleprescription($id)
    {
        $col = 'id';
        if (!intval($id) > 0):
            $id = decrypt($id);
            $col = 'medical_record_id';
        endif;
        $spectacle = Spectacle::where($col, $id)->first();
        if ($spectacle):
            $mrecord = DB::table('patient_medical_records')->find($spectacle->medical_record_id);
            $patient = PatientRegistrations::find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $reference = DB::table('patient_references')->find($mrecord->mrn);
            $branch = DB::table('branches')->find($reference->branch);
            $qrcode = base64_encode(QrCode::format('svg')->size(75)->errorCorrection('H')->generate('https://play.google.com/store/apps/details?id=com.devieh.virtualtoken'));
            $pdf = PDF::loadView('/pdf/spectacle-prescription', compact('patient', 'qrcode', 'reference', 'spectacle', 'mrecord', 'doctor', 'branch'));
            return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
        else:
            echo "No records found for spectacle prescription";
        endif;
    }

    public function radiologyprescription($id)
    {
        $labs = DB::table('lab_radiologies')->where('medical_record_id', $id)->where('tested_from', 0)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-radiology-prescription', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function radiologybill($id)
    {
        $bno = DB::table('lab_radiologies')->where('medical_record_id', $id)->select('bill_number')->first();
        $labs = DB::table('lab_radiologies')->where('medical_record_id', $id)->where('tested_from', 1)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-radiology-bill', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch', 'bno'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function radiologyreport($id)
    {
        $labs = DB::table('lab_radiologies')->where('medical_record_id', $id)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-radiology-report', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function clinicprescription($id)
    {
        $labs = DB::table('lab_clinics')->where('medical_record_id', $id)->where('tested_from', 0)->orderBy('order_by')->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-clinic-prescription', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function clinicbill($id)
    {
        $bno = DB::table('lab_clinics')->where('medical_record_id', $id)->select('bill_number')->first();
        $labs = DB::table('lab_clinics')->where('medical_record_id', $id)->where('tested_from', 1)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-clinic-bill', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch', 'bno'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function clinicreport($id)
    {
        $labs = DB::table('lab_clinics')->where('medical_record_id', $id)->orderBy('order_by')->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $reference = DB::table('patient_references')->find($mrecord->mrn);
        $branch = DB::table('branches')->find($reference->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/lab-clinic-report', compact('patient', 'qrcode', 'labs', 'mrecord', 'doctor', 'branch'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function pharmacyreceipt($id)
    {
        $record = DB::table('pharmacies')->find($id);
        $branch = DB::table('branches')->find($record->branch);
        $medicines = DB::table('pharmacy_records as pr')->leftJoin('products as p', 'pr.product', '=', 'p.id')->leftJoin('medicine_types as m', 'm.id', '=', 'pr.type')->select('p.product_name', 'pr.qty', 'pr.batch_number', 'pr.price', 'pr.discount', 'pr.tax', 'pr.tax_amount', 'pr.total', 'm.name as type')->where('pr.pharmacy_id', $id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/pharmacy-receipt', compact('record', 'medicines', 'qrcode', 'branch'));
        return $pdf->stream('receipt.pdf', array("Attachment" => 0));
    }
    public function patienthistory($id)
    {
        $id = (intval($id) > 0) ? $id : decrypt($id);
        $mrecords = DB::table('patient_medical_records')->where('patient_id', $id)->get();
        $onote = OperationNote::where('patient_id', $id);
        $patient = DB::table('patient_registrations')->where('id', $id)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));
        $pdf = PDF::loadView('/pdf/patient-history', compact('mrecords', 'qrcode', 'patient', 'onote'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }
    public function visioncertificate($id)
    {
        $patient = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->select('pr.patient_name', 'pr.age')->where('pmr.id', $id)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://emr.devihospitals.in/auth/certificate/$id/"));
        $pdf = PDF::loadView('/pdf/license/vision', compact('qrcode', 'patient'));
        return $pdf->stream('vision.pdf', array("Attachment" => 0));
    }
    public function medicalcertificate($id)
    {
        $patient = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->select('pr.patient_name', 'pr.age')->where('pmr.id', $id)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://emr.devihospitals.in/auth/certificate/$id/"));
        $pdf = PDF::loadView('/pdf/license/medical', compact('qrcode', 'patient'));
        return $pdf->stream('medical.pdf', array("Attachment" => 0));
    }
    public function campprint($id)
    {
        $camp = DB::table('camps')->find($id);
        $campm = DB::table('camp_masters')->find($camp->camp_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/camp', compact('qrcode', 'camp', 'campm'));
        return $pdf->stream('camp.pdf', array("Attachment" => 0));
    }
    public function campmasterprint($id)
    {
        $campm = DB::table('camp_masters')->find($id);
        $camps = DB::table('camps')->where('camp_id', $id)->get();
        $branch = DB::table('branches')->find($campm->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/campmaster', compact('qrcode', 'camps', 'campm', 'branch'));
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text($x = 50, $y = 800, $text = "Page {PAGE_NUM} of {PAGE_COUNT}", $font = null, $size = 10, $color = array(0, 0, 0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0);
        $canvas->page_text($x = 450, $y = 800, $text = $campm->camp_id, $font = null, $size = 10, $color = array(0, 0, 0), $word_space = 0.0, $char_space = 0.0, $angle = 0.0);
        return $pdf->stream('camp.pdf', array("Attachment" => 0));
    }
    public function tonometryreceipt($id)
    {
        $tonometry = DB::table('tonometries')->find($id);
        $patient = DB::table('patient_registrations')->find($tonometry->patient_id);
        $branch = DB::table('branches')->find($tonometry->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $tonometry->medical_record_id)->where('pp.type', 'T')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/tonometry/receipt', compact('qrcode', 'tonometry', 'patient', 'branch', 'procedures'));
        return $pdf->stream('tonometry.pdf', array("Attachment" => 0));
    }
    public function tonometryreport($id)
    {
        $tonometry = DB::table('tonometries')->find($id);
        $patient = DB::table('patient_registrations')->find($tonometry->patient_id);
        $branch = DB::table('branches')->find($tonometry->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/tonometry/report', compact('qrcode', 'tonometry', 'patient', 'branch'));
        return $pdf->stream('tonometry.pdf', array("Attachment" => 0));
    }
    public function keratometryreceipt($id)
    {
        $keratometry = DB::table('keratometries')->find($id);
        $patient = DB::table('patient_registrations')->find($keratometry->patient_id);
        $branch = DB::table('branches')->find($keratometry->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $keratometry->medical_record_id)->where('pp.type', 'K')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/keratometry/receipt', compact('qrcode', 'keratometry', 'patient', 'branch', 'procedures'));
        return $pdf->stream('tonometry.pdf', array("Attachment" => 0));
    }
    public function keratometryreport($id)
    {
        $keratometry = DB::table('keratometries')->find($id);
        $patient = DB::table('patient_registrations')->find($keratometry->patient_id);
        $branch = DB::table('branches')->find($keratometry->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/keratometry/report', compact('qrcode', 'keratometry', 'patient', 'branch'));
        return $pdf->stream('tonometry.pdf', array("Attachment" => 0));
    }
    public function ascanreceipt($id)
    {
        $ascan = DB::table('ascans')->find($id);
        $patient = DB::table('patient_registrations')->find($ascan->patient_id);
        $branch = DB::table('branches')->find($ascan->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $ascan->medical_record_id)->where('pp.type', 'A')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/ascan/receipt', compact('qrcode', 'ascan', 'patient', 'branch', 'procedures'));
        return $pdf->stream('tonometry.pdf', array("Attachment" => 0));
    }
    public function ascanreport($id)
    {
        $ascan = DB::table('ascans')->find($id);
        $keratometry = DB::table('keratometries')->where('medical_record_id', $ascan->medical_record_id)->first();
        $patient = DB::table('patient_registrations')->find($ascan->patient_id);
        $branch = DB::table('branches')->find($ascan->branch);
        $procedures = PatientProcedure::where('type', 'A')->where('medical_record_id', $ascan->medical_record_id)->get();
        $procs = Procedure::all();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/ascan/report', compact('qrcode', 'keratometry', 'patient', 'branch', 'ascan', 'procedures', 'procs'));
        return $pdf->stream('tonometry.pdf', array("Attachment" => 0));
    }
    public function visionreceipt($id)
    {
        $spectacle = DB::table('spectacles')->find($id);
        $pref = DB::table('patient_references')->find($spectacle->medical_record_id);
        $patient = DB::table('patient_registrations')->find($pref->patient_id);
        $branch = DB::table('branches')->find($pref->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/vision-receipt', compact('qrcode', 'spectacle', 'patient', 'branch', 'pref'));
        return $pdf->stream('vision-receipt.pdf', array("Attachment" => 0));
    }
    public function printletterhead($id)
    {
        $matter = DB::table('letter_heads')->find($id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/letterhead', compact('qrcode', 'matter'));
        return $pdf->stream('letterhead.pdf', array("Attachment" => 0));
    }
    public function printmfit($id)
    {
        $mfit = DB::table('medical_fitnesses')->find($id);
        $madvice = DB::table('surgery_types')->find($mfit->fitness_advice);
        $patient = DB::table('patient_registrations')->find($mfit->patient);
        $pref = DB::table('patient_references')->find($mfit->medical_record_id);
        $branch = DB::table('branches')->find($mfit->branch);
        $doctor = DB::table('doctors')->find($pref->doctor_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/medicalfitness', compact('qrcode', 'mfit', 'patient', 'pref', 'branch', 'doctor', 'madvice'));
        return $pdf->stream('letterhead.pdf', array("Attachment" => 0));
    }
    public function pachymetryreceipt($id)
    {
        $pachymetry = DB::table('pachymetries')->find($id);
        $patient = DB::table('patient_registrations')->find($pachymetry->patient_id);
        $branch = DB::table('branches')->find($pachymetry->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $pachymetry->medical_record_id)->where('pp.type', 'P')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/pachymetry/receipt', compact('qrcode', 'pachymetry', 'patient', 'branch', 'procedures'));
        return $pdf->stream('pachymetry.pdf', array("Attachment" => 0));
    }
    public function pachymetryreport($id)
    {
        $pachymetry = DB::table('pachymetries')->find($id);
        $patient = DB::table('patient_registrations')->find($pachymetry->patient_id);
        $branch = DB::table('branches')->find($pachymetry->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/pachymetry/report', compact('qrcode', 'pachymetry', 'patient', 'branch'));
        return $pdf->stream('pachymetry.pdf', array("Attachment" => 0));
    }
    public function purchasebill($id)
    {
        $purchase = DB::table('purchases')->find($id);
        $purchases = DB::table('purchase_details as pd')->leftJoin('products as p', 'p.id', '=', 'pd.product')->selectRaw("pd.*, p.product_name")->where('purchase_id', $purchase->id)->get();
        $tot = $purchases->sum('total');
        $supplier = DB::table('suppliers')->find($purchase->supplier);
        $pdf = PDF::loadView('/pdf/purchase-bill', compact('purchase', 'purchases', 'supplier', 'tot'));
        return $pdf->stream('invoice.pdf', array("Attachment" => 0));
    }
    public function producttransferbill($id)
    {
        $transfer = DB::table('product_transfers as pt')->leftJoin('branches as bf', 'pt.from_branch', '=', 'bf.id')->leftjoin('branches as bt', 'pt.to_branch', '=', 'bt.id')->selectRaw("pt.id, DATE_FORMAT(pt.transfer_date, '%d/%b/%Y') AS tdate, pt.transfer_note, CASE WHEN pt.from_branch = 0 THEN 'Main Branch' ELSE bf.branch_name END AS from_branch, CASE WHEN pt.to_branch = 0 THEN 'Main Branch' ELSE bt.branch_name END AS to_branch")->where('pt.id', $id)->get()->first();
        $tdetails = DB::table('product_transfer_details as td')->leftJoin('products as p', 'p.id', '=', 'td.product')->selectRaw("td.*, p.product_name")->where('transfer_id', $transfer->id)->get();
        $pdf = PDF::loadView('/pdf/product-transfer-bill', compact('transfer', 'tdetails'));
        return $pdf->stream('bill.pdf', array("Attachment" => 0));
    }
    public function patientpaymentbill($id)
    {
        $payment = DB::table('patient_payments')->find($id);
        $patient = DB::table('patient_registrations')->find($payment->patient_id);
        $branch = DB::table('branches')->find($payment->branch);
        $pdf = PDF::loadView('/pdf/patient-outstanding', compact('payment', 'patient', 'branch'));
        return $pdf->stream('Payment.pdf', array("Attachment" => 0));
    }

    public function printonote($id)
    {
        $onote = OperationNote::find($id);
        $patient = DB::table('patient_registrations')->find($onote->patient_id);
        $pref = DB::table('patient_references')->find($onote->medical_record_id);
        $branch = DB::table('branches')->find($onote->branch);
        $doctor = DB::table('doctors')->find($pref->doctor_id);
        $pdf = PDF::loadView('/pdf/onote', compact('onote', 'patient', 'branch', 'doctor'));
        return $pdf->stream('onote.pdf', array("Attachment" => 0));
    }

    public function hfareceipt($id)
    {
        $hfa = DB::table('h_f_a_s')->find($id);
        $patient = DB::table('patient_registrations')->find($hfa->patient_id);
        $branch = DB::table('branches')->find($hfa->branch);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $hfa->medical_record_id)->where('pp.type', 'H')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $mrecord = PatientMedicalRecord::find($hfa->medical_record_id);
        $doctor = doctor::find($mrecord->doctor_id ?? 0);
        $pdf = PDF::loadView('/pdf/hfa/receipt', compact('qrcode', 'hfa', 'patient', 'branch', 'procedures', 'mrecord', 'doctor'));
        return $pdf->stream('hfa.pdf', array("Attachment" => 0));
    }

    public function octreceipt($id)
    {
        $oct = DB::table('octs')->find($id);
        $patient = DB::table('patient_registrations')->find($oct->patient_id);
        $branch = DB::table('branches')->find($oct->branch_id);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $oct->medical_record_id)->where('pp.type', 'O')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $mrecord = PatientMedicalRecord::find($oct->medical_record_id);
        $doctor = doctor::find($mrecord->doctor_id ?? 0);
        $pdf = PDF::loadView('/pdf/oct/receipt', compact('qrcode', 'oct', 'patient', 'branch', 'procedures', 'mrecord', 'doctor'));
        return $pdf->stream('oct.pdf', array("Attachment" => 0));
    }

    public function bscanreceipt($id)
    {
        $bscan = DB::table('bscans')->find($id);
        $patient = DB::table('patient_registrations')->find($bscan->patient_id);
        $branch = DB::table('branches')->find($bscan->branch_id);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $bscan->medical_record_id)->where('pp.type', 'B')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $mrecord = PatientMedicalRecord::find($bscan->medical_record_id);
        $doctor = doctor::find($mrecord->doctor_id ?? 0);
        $pdf = PDF::loadView('/pdf/bscan/receipt', compact('qrcode', 'bscan', 'patient', 'branch', 'procedures', 'mrecord', 'doctor'));
        return $pdf->stream('bscan.pdf', array("Attachment" => 0));
    }

    public function laserreceipt($id)
    {
        $laser = DB::table('lasers')->find($id);
        $patient = DB::table('patient_registrations')->find($laser->patient_id);
        $branch = DB::table('branches')->find($laser->branch_id);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $laser->medical_record_id)->where('pp.type', 'G')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $mrecord = PatientMedicalRecord::find($laser->medical_record_id);
        $doctor = doctor::find($mrecord->doctor_id ?? 0);
        $pdf = PDF::loadView('/pdf/laser/receipt', compact('qrcode', 'laser', 'patient', 'branch', 'procedures', 'mrecord', 'doctor'));
        return $pdf->stream('laser.pdf', array("Attachment" => 0));
    }

    public function surgeryconsumablereceipt($id)
    {
        $psc = PatientSurgeryConsumable::find($id);
        $pdf = PDF::loadView('/pdf/surgery-consumable-receipt', compact('psc'));
        return $pdf->stream('suregry-consumables-receipt.pdf', array("Attachment" => 0));
    }

    public function dsummary($id)
    {
        $ds = DischargeSummary::find($id);
        $diagnosis = DischargeSummaryDiagnosis::leftJoin('diagnosis as d', 'd.id', '=', 'discharge_summary_diagnoses.diagnosis')->pluck('d.diagnosis_name')->implode(', ');
        $procedure = Procedure::all();
        $pdf = PDF::loadView('/pdf/dsummary', compact('ds', 'diagnosis', 'procedure'));
        return $pdf->stream('discharge-summary.pdf', array("Attachment" => 0));
    }

    public function patientTransactionHistory($id)
    {
        $patient = PatientRegistrations::findOrFail($id);
        $mrns = PatientReference::where('patient_id', $id)->latest()->get();
        $branch = DB::table('branches')->find($patient->branch);
        $pdf = PDF::loadView('/pdf/patient-transaction-history', compact('patient', 'mrns', 'branch'));
        return $pdf->stream($patient->patient_id . '.pdf', array("Attachment" => 0));
    }

    public function patientTransactionHistoryMrn($id)
    {
        $mrn = PatientReference::findOrFail($id);
        $patient = PatientRegistrations::findOrFail($mrn->patient_id);
        $branch = Branch::findOrFail($mrn->branch);
        $pdf = PDF::loadView('/pdf/patient-transaction-history-mrn', compact('patient', 'mrn', 'branch'));
        return $pdf->stream($mrn->id . '.pdf', array("Attachment" => 0));
    }

    public function axialLengthReceipt($id)
    {
        $ax = AxialLength::find($id);
        $patient = DB::table('patient_registrations')->find($ax->patient_id);
        $branch = DB::table('branches')->find($ax->branch_id);
        $procedures = DB::table('patient_procedures  as pp')->leftJoin('procedures as p', 'p.id', 'pp.procedure')->select('p.name', 'pp.fee', 'pp.discount')->whereNull('pp.deleted_at')->where('pp.medical_record_id', $ax->medical_record_id)->where('pp.type', 'L')->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/axial-length/receipt', compact('qrcode', 'ax', 'patient', 'branch', 'procedures'));
        return $pdf->stream('axial-length.pdf', array("Attachment" => 0));
    }
    public function axialLengthReport($id)
    {
        $ax = AxialLength::find($id);
        $procedures = PatientProcedure::where('type', 'L')->where('medical_record_id', $ax->medical_record_id)->get();
        $procs = Procedure::all();
        $patient = DB::table('patient_registrations')->find($ax->patient_id);
        $branch = DB::table('branches')->find($ax->branch_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('/pdf/axial-length/report', compact('qrcode', 'ax', 'patient', 'branch', 'procs', 'procedures'));
        return $pdf->stream('axial-length.pdf', array("Attachment" => 0));
    }

    public function patientAcknowledgement($id)
    {
        $ack = PatientAcknoledgement::find($id);
        $surgery = Surgery::where('medical_record_id', $ack->medical_record_id)->first();
        $procs = DB::table('types')->where('category', 'ack')->get();
        $ackproc = PatientAcknowledgementProcedure::where('patient_acknowledgement_id', $id)->pluck('procedure_id')->all();
        $doctor = doctor::find(PatientReference::find($ack->medical_record_id)->doctor_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate("https://devieh.com/online"));
        $pdf = PDF::loadView('pdf.acknowledgement', compact('qrcode', 'procs', 'ackproc', 'ack', 'doctor', 'surgery'));
        return $pdf->stream('patient-ack.pdf', array("Attachment" => 0));
    }
}
