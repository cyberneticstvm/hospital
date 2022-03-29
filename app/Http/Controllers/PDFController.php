<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientReference as PRef;
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
        return $pdf->stream('token', array("Attachment"=>0));
    }
}
