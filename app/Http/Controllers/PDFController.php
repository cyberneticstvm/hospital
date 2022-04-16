<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientReference as PRef;
use QrCode;
use PDF;
use DB;

class PDFController extends Controller
{
    public function token($id){
        $data = PRef::find($id);
        $patient = DB::table('patient_registrations')->find($data->patient_id);     
        $doctor = DB::table('doctors')->find($data->doctor_id);     
        //view()->share('patient', $data);     
        $pdf = PDF::loadView('/pdf/token', ['reference' => $data, 'patient' => $patient, 'doctor' => $doctor]);    
        //return $pdf->download('token.pdf');
        return $pdf->stream('token.pdf', array("Attachment"=>0));
    }

    public function prescription($id){
        $reference = PRef::find($id);
        $patient = DB::table('patient_registrations')->find($reference->patient_id);     
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));     
        //view()->share('patient', $reference);     
        $pdf = PDF::loadView('/pdf/prescription', compact('reference', 'patient', 'doctor', 'qrcode'));    
        //return $pdf->download('token.pdf');
        return $pdf->stream('prescription.pdf', array("Attachment"=>0));
    }

    public function receipt($id){
        $reference = PRef::find($id);
        $patient = DB::table('patient_registrations')->find($reference->patient_id);     
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/receipt', compact('reference', 'patient', 'doctor', 'qrcode', 'branch'));    
        return $pdf->stream('receipt.pdf', array("Attachment"=>0));
    }

    public function pharmacybill($id){
        $medical_record = DB::table('patient_medical_records')->find($id);
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->select('p.product_name', 'p.hsn', 'm.batch_number', 'm.qty', 'm.price', 'm.tax_percentage', 'm.total', 'pd.expiry_date')->leftJoin('purchase_details as pd', function($join){
            $join->on('pd.product', '=', 'm.medicine')->on('pd.batch_number', '=', 'm.batch_number');
        })->where('m.medical_record_id', $id)->groupBy('p.product_name', 'p.hsn', 'm.batch_number', 'm.qty', 'm.price', 'm.tax_percentage', 'm.total', 'pd.expiry_date')->get();
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id);     
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/pharmacy-bill', compact('patient', 'doctor', 'qrcode', 'branch', 'medical_record', 'medicines'));    
        return $pdf->stream('pharmacy-bill.pdf', array("Attachment"=>0));
    }

    public function medicalrecord($id){
        $record = DB::table('patient_medical_records')->find($id);
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->select('p.product_name', 'm.dosage', 'm.notes', 'm.qty')->where('m.medical_record_id', $id)->get();
        $patient = DB::table('patient_registrations')->find($record->patient_id);
        $reference = DB::table('patient_references')->find($record->mrn);     
        $doctor = DB::table('doctors')->find($record->doctor_id);
        $branch = DB::table('branches')->find($reference->branch);
        $sympt = explode(',', $record->symptoms);
        $diag = explode(',', $record->diagnosis);
        $symptoms = DB::table('symptoms')->whereIn('id', $sympt)->get();
        $diagnosis = DB::table('diagnosis')->whereIn('id', $diag)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/medical-record', compact('record', 'patient', 'doctor', 'qrcode', 'branch', 'reference', 'symptoms', 'diagnosis', 'medicines'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }

    public function medicalrecordhistory($id){
        $patient = DB::table('patient_registrations')->find($id);
        $references = DB::table('patient_references')->where('patient_id', $id)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate('https://devieh.com/online'));         
        $pdf = PDF::loadView('/pdf/medical-record-history', compact('patient', 'qrcode', 'references'));    
        return $pdf->stream($patient->patient_id.'.pdf', array("Attachment"=>0));
    }
}
