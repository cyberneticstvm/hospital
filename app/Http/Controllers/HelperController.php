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
    public function getProductForTransfer(Request $request){
        if($request->branch == 0):
            $inventory = DB::select("SELECT tbl1.product, tbl1.batch_number AS batch_number, tbl1.purchased AS purchased, SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS transferred, tbl1.purchased-SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS balance_qty FROM (SELECT p.id, p.product, SUM(p.qty) AS purchased, p.batch_number FROM purchase_details p WHERE p.product = ? GROUP BY p.batch_number) AS tbl1 LEFT JOIN product_transfer_details t ON tbl1.product = t.product LEFT JOIN product_transfers pt ON pt.id=t.transfer_id GROUP BY tbl1.id HAVING balance_qty > 0", [$request->product]);
        else:
            $inventory = DB::select("SELECT tbl4.batch_number, tbl4.balance-(tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty) AS balance_qty FROM (SELECT tbl3.*, IFNULL(SUM(CASE WHEN tbl3.batch_number = po.batch_number AND pm.branch = ? AND pm.bill_generated = 1 THEN po.qty END), 0) AS surgery_qty FROM (SELECT tbl2.*, IFNULL(SUM(CASE WHEN tbl2.batch_number = p.batch_number AND ph.branch = ? THEN p.qty END), 0) AS pharmaqty FROM (SELECT tbl1.*, IFNULL(SUM(CASE WHEN tbl1.batch_number = m.batch_number AND pmr.branch = ? AND m.status = 1 THEN m.qty END), 0) AS medqty FROM (SELECT tblTrnsf.*, tblTrnsf.received-tblTrnsf.transferred AS balance FROM (SELECT pd.product, pd.batch_number, IFNULL(SUM(CASE WHEN pt.to_branch = ? THEN pd.qty END), 0) AS received, IFNULL(SUM(CASE WHEN pt.from_branch = ? THEN pd.qty END), 0) AS transferred FROM product_transfer_details pd LEFT JOIN product_transfers pt ON pd.transfer_id = pt.id GROUP BY pd.batch_number) AS tblTrnsf) AS tbl1 LEFT JOIN patient_medicine_records m ON m.medicine = tbl1.product LEFT JOIN patient_medical_records pmr ON pmr.id = m.medical_record_id GROUP BY tbl1.batch_number) AS tbl2 LEFT JOIN pharmacy_records p ON p.product = tbl2.product LEFT JOIN pharmacies ph ON ph.id = p.pharmacy_id GROUP BY tbl2.batch_number) AS tbl3 LEFT JOIN post_operative_medicine_details po ON po.product = tbl3.product LEFT JOIN post_operative_medicines pm ON pm.id = po.pom_id GROUP BY tbl3.batch_number) AS tbl4 WHERE tbl4.product = ? HAVING balance_qty > 0", [$request->branch, $request->branch, $request->branch, $request->branch, $request->branch, $request->product]);
        endif;
        $op = "<option value=''>Select</option>";
        if($inventory):
            foreach($inventory as $key => $inv):
                $op .= "<option value='".$inv->batch_number."'>".$inv->batch_number."  (".$inv->balance_qty." Qty in Hand)</option>";
            endforeach;
        else:
            $op .= "<option value='NRF'>No records found</option>";
        endif;
        echo $op;
    }
    public static function getProductForTransferForEdit($product, $branch){
        if($branch == 0):
            $inventory = DB::select("SELECT tbl1.product, tbl1.batch_number AS batch_number, tbl1.purchased AS purchased, SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS transferred, tbl1.purchased-SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS balance_qty FROM (SELECT p.id, p.product, SUM(p.qty) AS purchased, p.batch_number FROM purchase_details p WHERE p.product = ? GROUP BY p.batch_number) AS tbl1 LEFT JOIN product_transfer_details t ON tbl1.product = t.product LEFT JOIN product_transfers pt ON pt.id=t.transfer_id GROUP BY tbl1.id HAVING balance_qty > 0", [$product]);
        else:
            $inventory = DB::select("SELECT tbl4.batch_number, tbl4.balance-(tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty) AS balance_qty FROM (SELECT tbl3.*, IFNULL(SUM(CASE WHEN tbl3.batch_number = po.batch_number AND pm.branch = ? AND pm.bill_generated = 1 THEN po.qty END), 0) AS surgery_qty FROM (SELECT tbl2.*, IFNULL(SUM(CASE WHEN tbl2.batch_number = p.batch_number AND ph.branch = ? THEN p.qty END), 0) AS pharmaqty FROM (SELECT tbl1.*, IFNULL(SUM(CASE WHEN tbl1.batch_number = m.batch_number AND pmr.branch = ? AND m.status = 1 THEN m.qty END), 0) AS medqty FROM (SELECT tblTrnsf.*, tblTrnsf.received-tblTrnsf.transferred AS balance FROM (SELECT pd.product, pd.batch_number, IFNULL(SUM(CASE WHEN pt.to_branch = ? THEN pd.qty END), 0) AS received, IFNULL(SUM(CASE WHEN pt.from_branch = ? THEN pd.qty END), 0) AS transferred FROM product_transfer_details pd LEFT JOIN product_transfers pt ON pd.transfer_id = pt.id GROUP BY pd.batch_number) AS tblTrnsf) AS tbl1 LEFT JOIN patient_medicine_records m ON m.medicine = tbl1.product LEFT JOIN patient_medical_records pmr ON pmr.id = m.medical_record_id GROUP BY tbl1.batch_number) AS tbl2 LEFT JOIN pharmacy_records p ON p.product = tbl2.product LEFT JOIN pharmacies ph ON ph.id = p.pharmacy_id GROUP BY tbl2.batch_number) AS tbl3 LEFT JOIN post_operative_medicine_details po ON po.product = tbl3.product LEFT JOIN post_operative_medicines pm ON pm.id = po.pom_id GROUP BY tbl3.batch_number) AS tbl4 WHERE tbl4.product = ? HAVING balance_qty > 0", [$branch, $branch, $branch, $branch, $branch, $product]);
        endif;
        /*$op = "<option value=''>Select</option>";
        if($inventory):
            foreach($inventory as $key => $inv):
                $op .= "<option value='".$inv->batch_number."'>".$inv->batch_number."  (".$inv->balance_qty." Qty in Hand)</option>";
            endforeach;
        else:
            $op .= "<option value='NRF'>No records found</option>";
        endif;
        return $op;*/
        return $inventory;
    }
    public function getProductPrice(Request $request){
        $price = DB::table('purchase_details')->where('batch_number', $request->batch_number)->where('product', $request->product)->first();
        echo $price->price;
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
        if($request->type == 'pharmacy'): // Direct
            $html = $this->getPharmacyDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'medicine'):
            $html = $this->getMedicineDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'vision'):
            $html = $this->getVisionDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'incomecash'):
            $html = $this->getIncomeCashDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'incomeupi'):
            $html = $this->getIncomeUpiDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'incomecard'):
            $html = $this->getIncomeCardDetailed($fdate, $tdate, $branch);
        endif;
        if($request->type == 'incomepending'):
            $html = $this->getIncomePendingDetailed($fdate, $tdate, $branch);
        endif;
        echo $html;
    }
    public function getInventoryDetailed(Request $request){
        $html = ""; $product = $request->product; $batch = $request->batch; $branch = $request->branch;
        if($request->type == 'stockin'):
            $html = $this->getStockInDetailed($product, $batch, $branch);
        endif;
        if($request->type == 'stockout'):
            $html = $this->getStockOutDetailed($product, $batch, $branch);
        endif;
        echo $html;
    }
    public function getStockOutDetailed($product, $batch, $branch){
        $outs = DB::table('product_transfer_details as pd')->leftJoin('product_transfers as pt', 'pt.id', '=', 'pd.transfer_id')->leftJoin('products as pr', 'pr.id', '=', 'pd.product')->leftJoin('branches as b', 'b.id', '=', 'to_branch')->selectRaw("'Transfer' AS type, SUM(pd.qty) AS qty, pd.batch_number, pr.product_name, DATE_FORMAT(pt.transfer_date, '%d/%b/%Y') AS pdate, b.branch_name")->where('pt.from_branch', $branch)->where('pd.product', $product)->where('pd.batch_number', $batch)->groupBy('pd.batch_number')->orderBy('pt.transfer_date');

        $meds = DB::table("patient_medicine_records as m")->leftjoin('patient_medical_records as pmr', 'pmr.id', '=', 'm.medical_record_id')->leftJoin('products as pr', 'pr.id', '=', 'm.medicine')->selectRaw("'Medicine' AS type, m.qty, m.batch_number, pr.product_name, DATE_FORMAT(pmr.created_at, '%d/%b/%Y') AS pdate, m.medical_record_id AS branch_name")->where('pmr.branch', $branch)->where('m.medicine', $product)->where('m.batch_number', $batch)->unionAll($outs);

        $pharmacy = DB::table("pharmacy_records as pr")->leftjoin('pharmacies as p', 'p.id', '=', 'pr.pharmacy_id')->leftJoin('products as pro', 'pro.id', '=', 'pr.product')->selectRaw("'Med Out' AS type, pr.qty, pr.batch_number, pro.product_name, DATE_FORMAT(p.created_at, '%d/%b/%Y') AS pdate, p.patient_name AS branch_name")->where('p.branch', $branch)->where('pr.product', $product)->where('pr.batch_number', $batch)->unionAll($meds);

        $surgery = DB::table("post_operative_medicine_details as pd")->leftJoin("post_operative_medicines as pm", 'pm.id', '=', 'pd.pom_id')->leftJoin('products as pro', 'pro.id', '=', 'pd.product')->selectRaw("pm.type, pd.qty, pd.batch_number, pro.product_name, DATE_FORMAT(pm.created_at, '%d/%b/%Y') AS pdate, pm.medical_record_id AS branch_name")->where('pm.branch', $branch)->where('pd.product', $product)->where('pd.batch_number', $batch)->where('pm.bill_generated', 1)->unionAll($pharmacy)->get();

        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Product</th><th>Batch Number</th><th>Transfer Date</th><th>Trans. To</th><th>Type</th><th>Qty</th></tr></thead><tbody>";
        $c = 1;
        foreach($surgery as $key => $record):
            $html .= "<tr>";
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$record->product_name."</td>";
                $html .= "<td>".$record->batch_number."</td>";
                $html .= "<td>".$record->pdate."</td>";
                $html .= "<td>".$record->branch_name."</td>";
                $html .= "<td>".$record->type."</td>";
                $html .= "<td class='text-end'>".$record->qty."</td>";
            $html .= "</tr>";
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='6' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".$surgery->sum('qty')."</td></tr></tfoot></table>";
        return $html;
    }
    public function getStockInDetailed($product, $batch, $branch){
        if($branch == 0):
            $ins = DB::table('purchase_details as pd')->leftJoin('purchases as p', 'p.id', '=', 'pd.purchase_id')->leftJoin('products as pr', 'pr.id', '=', 'pd.product')->selectRaw("SUM(pd.qty) AS qty, pd.batch_number, pr.product_name, DATE_FORMAT(p.delivery_date, '%d/%b/%Y') AS pdate")->where('pd.product', $product)->groupBy('pd.batch_number')->orderBy('p.delivery_date')->get();
        else:
            $ins = DB::table('product_transfer_details as pd')->leftJoin('product_transfers as pt', 'pt.id', '=', 'pd.transfer_id')->leftJoin('products as pr', 'pr.id', '=', 'pd.product')->selectRaw("SUM(pd.qty) AS qty, pd.batch_number, pr.product_name, DATE_FORMAT(pt.transfer_date, '%d/%b/%Y') AS pdate")->where('pt.to_branch', $branch)->where('pd.product', $product)->groupBy('pd.batch_number')->orderBy('pt.transfer_date')->get();
        endif;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Product</th><th>Batch Number</th><th>Purchase Date</th><th>Qty</th></tr></thead><tbody>";
        $c = 1;
        foreach($ins as $key => $record):
            $html .= "<tr>";
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$record->product_name."</td>";
                $html .= "<td>".$record->batch_number."</td>";
                $html .= "<td>".$record->pdate."</td>";
                $html .= "<td class='text-end'>".$record->qty."</td>";
            $html .= "</tr>";
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='4' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".$ins->sum('qty')."</td></tr></tfoot></table>";
        return $html;
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
    private function getPharmacyDetailed($fdate, $tdate, $branch){
        $pharmacy = DB::table('pharmacy_records as pr')->leftJoin('pharmacies as p', 'p.id', '=', 'pr.pharmacy_id')->select('p.id', 'p.patient_name', 'p.other_info', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate, SUM(pr.total) AS fee"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->groupBy('pr.pharmacy_id')->orderBy('p.patient_name', 'asc')->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Bill No.</th><th>Patient Name</th><th>Address</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($pharmacy as $key => $record):
            $html .= "<tr>";
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$record->id."</td>";
                $html .= "<td>".$record->patient_name."</td>";
                $html .= "<td>".$record->other_info."</td>";
                $html .= "<td>".$record->cdate."</td>";
                $html .= "<td class='text-end'>".$record->fee."</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".number_format($tot, 2)."</td></tr></tfoot></table>";
        return $html;
    }
    private function getMedicineDetailed($fdate, $tdate, $branch){
        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.id as mrid', DB::raw("SUM(m.total) AS fee, DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('m.status', 1)->whereBetween('p.created_at', [$fdate, $tdate])->where('p.branch', $branch)->groupBy('m.medical_record_id')->orderByDesc('m.medical_record_id')->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($medicine as $key => $record):
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
    private function getVisionDetailed($fdate, $tdate, $branch){
        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->leftJoin('patient_registrations as p', 'm.patient_id', '=', 'p.id')->where('m.branch', $branch)->where('s.fee', '>', 0)->whereBetween('s.created_at', [$fdate, $tdate])->select('p.patient_name', 'p.patient_id', 'm.id as mrid', 's.fee', DB::raw("DATE_FORMAT(s.created_at, '%d/%b/%Y') AS cdate"))->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($vision as $key => $record):
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
    private function getIncomeCashDetailed($fdate, $tdate, $branch){
        $income = DB::table('patient_payments as p')->leftjoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.medical_record_id as mrid', 'p.amount as fee', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->where('p.payment_mode', 1)->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($income as $key => $record):
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
    private function getIncomeUpiDetailed($fdate, $tdate, $branch){
        $income = DB::table('patient_payments as p')->leftjoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.medical_record_id as mrid', 'p.amount as fee', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->whereIn('p.payment_mode', [3,4])->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($income as $key => $record):
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
    private function getIncomeCardDetailed($fdate, $tdate, $branch){
        $income = DB::table('patient_payments as p')->leftjoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.medical_record_id as mrid', 'p.amount as fee', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->whereIn('p.payment_mode', [2,5,7])->get();
        $c = 1; $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach($income as $key => $record):
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
    private function getIncomePendingDetailed($fdate, $tdate, $branch){
        $records = DB::table('patient_medical_records as p')->leftJoin('patient_registrations as preg', 'p.patient_id', '=', 'preg.id')->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->select('p.id as mrid', 'preg.patient_name', 'preg.patient_id')->get();
        $c = 1; $owed_tot = 0; $paid_tot = 0; $tot = 0; $cls = '';
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Owed</th><th>Paid</th><th>Due</th></tr></thead><tbody>";
        foreach($records as $row):
            $html .= "<tr>";
                $owed = $this->getOwedTotal($row->mrid);
                $paid = $this->getPaidTotal($row->mrid);
                $cls = ($owed - $paid > 0) ? 'text-danger' : '';
                $html .= "<td>".$c++."</td>";
                $html .= "<td>".$row->mrid."</td>";
                $html .= "<td>".$row->patient_name."</td>";
                $html .= "<td>".$row->patient_id."</td>";
                $html .= "<td class='text-end'>".number_format($owed, 2)."</td>";
                $html .= "<td class='text-end'>".number_format($paid, 2)."</td>";
                $html .= "<td class='text-end ".$cls."'>".number_format($owed - $paid, 2)."</td>";
            $html .= "</tr>";
            $paid_tot += $paid; $owed_tot += $owed; $tot += $owed - $paid;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='4' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>".number_format($owed_tot, 2)."</td><td class='text-end fw-bold'>".number_format($paid_tot, 2)."</td><td class='text-end fw-bold ".$cls."'>".number_format($tot, 2)."</td></tr></tfoot></table>";
        return $html;
    }
    public function getOwedTotal($mrid){
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->where('pmr.id', $mrid)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $mrid)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.medical_record_id', $mrid)->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.medical_record_id', $mrid)->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('p.id', $mrid)->where('m.status', 1)->sum('m.total');

        $vision = DB::table('spectacles')->where('medical_record_id', $mrid)->sum('fee');

        return $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $medicine + $vision;
    }
    public function getPaidTotal($mrid){
        $paid = DB::table('patient_payments as p')->where('p.medical_record_id', $mrid)->sum('amount');
        return $paid;
    }
    public function getPreviousDues($patient_id){

    }
    public function certificateAuthentication($id){
        $patient = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->select('pr.patient_name', 'pr.age', 'pmr.doctor_id', 'pmr.branch', 'pr.address')->where('pmr.id', $id)->first();
        $doctor = DB::table('doctors')->find($patient->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $certificate = DB::table('patient_certificates as pc')->where('pc.medical_record_id', $id)->first();

        $certs = DB::table('patient_certificate_details as pcd')->leftJoin('certificate_types as ct', 'pcd.certificate_type', '=', 'ct.id')->leftJoin('patient_certificates as pc', 'pc.id', '=', 'pcd.patient_certificate_id')->select(DB::raw("(GROUP_CONCAT(ct.name SEPARATOR ', ')) as certs"))->where('pc.medical_record_id', $id)->where('pcd.status', 'I')->value('certs');

        $details = DB::table('patient_certificate_details as pcd')->select(DB::raw("DATE_FORMAT(pcd.created_at, '%d/%b/%Y %h:%i %p') AS created_at"))->where('pcd.patient_certificate_id', $certificate->id)->where('pcd.status', 'I')->first();
        return view('authentication.certificate', compact('certificate', 'details', 'patient', 'doctor', 'branch', 'certs'));
    }

    public function getlabtests(Request $request){
        $sid = $request->sid; $op = "";
        $tests = DB::table('lab_types')->selectRaw("id, lab_type_name, tested_from, CASE WHEN tested_from = 0 THEN 'Outside Laboratory' ELSE 'Own Laboratory' END AS lab")->where('surgery_type', $sid)->get();
        if($tests->isNotEmpty()):
            foreach($tests as $key => $test):
                $op .= "<div class='row mt-3'><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2' data-placeholder='Select' name='test_id[]' required='required'><option value='".$test->id."'>".$test->lab_type_name."</option></select></div><div class='col-sm-5'><input type='text' name='notes[]' class='form-control' placeholder='Notes' /></div><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2' data-placeholder='Select' name='tested_from[]' required='required'><option value='".$test->tested_from."'>".$test->lab."</option></select></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>";
            endforeach;
        else:
            $op = "";
        endif;        
        echo $op;
    }

    public function updatesmsstatus(Request $request){
        $mrid = $request->mrid; $chk = $request->chk;
        DB::table('patient_references')->where('id', $mrid)->update(['sms' => $chk]);
    }
}
