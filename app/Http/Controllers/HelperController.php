<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class HelperController extends Controller
{
    public function getMedicineType($mid){
        $data = DB::table('medicine_types as m')->leftJoin('products as p', 'm.id', '=', 'p.medicine_type')->select('m.id', 'm.name', 'm.default_qty', 'm.default_dosage')->where('p.id', $mid)->first();
        return response()->json($data);
    }
    public function getDayBookDetailed(Request $request){
        $html = "";
        $fdate = Carbon::createFromFormat('d/M/Y', $request->fdate)->startOfDay();
        $tdate = Carbon::createFromFormat('d/M/Y', $request->tdate)->endOfDay();
        $branch = $request->branch;
        if($request->type == 'consultation'):
            $html = $this->getConsultationDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'procedure'):
            $html = $this->getProcedureDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'certificate'):
            $html = $this->getCertificateDetailed($fdate, $tdate, $branch);
        endif;
        echo $html;
    }
    private function getConsultationDetailed($fdate, $tdate, $branch){
        $consultation = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'pr.patient_id')->select('preg.patient_name', 'preg.patient_id', 'pmr.id as mrid', 'pr.doctor_fee AS fee', DB::raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('pr.created_at', [$fdate, $tdate])->where('pr.branch', $branch)->where('pr.status', 1)->orderByDesc('pmr.id')->get();
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Reg.Date</th><th>Amount</th></tr></thead><tbody>";
        $c = 1; $tot = 0;
        foreach($consultation as $key => $record):
            $html .= "<tr>";
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$record->mrid."</td>";
                $html .= "<td>".$record->patient_name."</td>";
                $html .= "<td>".$record->patient_id."</td>";
                $html .= "<td>".$record->rdate."</td>";
                $html .= "<td class='text-end'>".$record->fee."</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".number_format($tot, 2)."</td></tr></tfoot></table>";
        return $html;
    }
    private function getProcedureDetailed($fdate, $tdate, $branch){
        $procedure = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 'pp.medical_record_id')->leftJoin('patient_registrations as pr', 'pr.id', '=', 'pmr.patient_id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id as mrid', 'pr.patient_name', 'pr.patient_id', DB::raw("SUM(pp.fee) as fee, DATE_FORMAT(pp.created_at, '%d/%b/%Y') AS cdate"))->whereBetween('pp.created_at', [$fdate, $tdate])->where('pp.branch', $branch)->groupBy('pp.medical_record_id')->orderByDesc('pmr.id')->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Procedures</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($procedure as $key => $record):
            $html .= "<tr>";
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$record->mrid."</td>";
                $html .= "<td>".$record->patient_name."</td>";
                $html .= "<td>".$record->patient_id."</td>";
                $html .= "<td>".$record->procs."</td>";
                $html .= "<td>".$record->cdate."</td>";
                $html .= "<td class='text-end'>".$record->fee."</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='6' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".number_format($tot, 2)."</td></tr></tfoot></table>";
        return $html;
    }
    private function getCertificateDetailed($fdate, $tdate, $branch){
        $certificate = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->leftJoin('patient_references as pr', 'pr.id', '=', 'pc.medical_record_id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'pr.patient_id')->select('preg.patient_name', 'preg.patient_id', 'pr.id as mrid', DB::raw("SUM(pcd.fee) AS fee, DATE_FORMAT(pc.created_at, '%d/%b/%Y') AS cdate"), )->whereBetween('pc.created_at', [$fdate, $tdate])->where('pc.branch_id', $branch)->where('pcd.status', 'I')->groupBy('pcd.patient_certificate_id')->orderByDesc('pc.medical_record_id')->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($certificate as $key => $record):
            $html .= "<tr>";
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$record->mrid."</td>";
                $html .= "<td>".$record->patient_name."</td>";
                $html .= "<td>".$record->patient_id."</td>";
                $html .= "<td>".$record->cdate."</td>";
                $html .= "<td class='text-end'>".$record->fee."</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".number_format($tot, 2)."</td></tr></tfoot></table>";
        return $html;
    }
}
