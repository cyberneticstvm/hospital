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
        echo $html;
    }
    private function getConsultationDetailed($fdate, $tdate, $branch){
        $consultation = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'pr.patient_id')->select('preg.patient_name', 'preg.patient_id', 'pmr.id as mrid', DB::raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate, SUM(pr.doctor_fee) AS fee"))->whereBetween('pr.created_at', [$fdate, $tdate])->where('pr.branch', $branch)->where('pr.status', 1)->groupBy('pmr.id')->orderByDesc('pmr.id')->get();
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
}
