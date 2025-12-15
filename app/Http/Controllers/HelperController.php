<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Mail\SendDocuments;
use App\Models\Branch;
use App\Models\DocumentTrack;
use App\Models\OperationNote;
use App\Models\PatientMedicalRecord;
use App\Models\PatientPayment;
use App\Models\PatientRegistrations;
use App\Models\PatientSurgeryConsumable;
use App\Models\Product;
use App\Models\PromotionContact;
use App\Models\PromotionSchedule;
use App\Models\Spectacle;
use App\Models\SurgeryConsumableItem;
use App\Models\UserBranch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HelperController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:send-documents', ['only' => ['waDocs', 'emailDocs']]);
        $this->middleware('permission:iol-power-calculator', ['only' => ['iolPower', 'calculateIolPower']]);
    }
    public function getMedicineType($mid)
    {
        $data = DB::table('product_categories as m')->leftJoin('products as p', 'm.id', '=', 'p.medicine_type')->select('m.id', 'm.name', 'm.default_qty', 'm.default_dosage')->where('p.id', $mid)->first();
        return response()->json($data);
    }

    function getStockStatus(Request $request)
    {
        $branches = Branch::pluck('branch_name', 'id');
        $products = Product::pluck('product_name', 'id');
        $inputs = array(Session::get('branch'), 0);
        $stock = collect(Helper::getStock(0, Session::get('branch'), 0));
        return view('pharmacy.stock.stock', compact('stock', 'branches', 'products', 'inputs'));
    }

    function getStockStatusUpdate(Request $request)
    {
        $this->validate($request, [
            'branch_id' => 'required',
        ]);
        $branches = Branch::pluck('branch_name', 'id');
        $products = Product::pluck('product_name', 'id');
        $inputs = array($request->branch_id, $request->product_id ?? '');
        $stock = collect(Helper::getStock($request->product_id ?? 0, $request->branch_id, 0));
        return view('pharmacy.stock.stock', compact('stock', 'branches', 'products', 'inputs'));
    }

    public function getProductForTransfer(Request $request)
    {
        if ($request->branch == 0) :
            $inventory = DB::select("SELECT 'Main Branch' AS branch, tbl1.id as product, tbl1.product_name, tbl1.batch_number, tbl1.purchased, SUM(CASE WHEN tbl1.batch_number = ptd.batch_number AND t.from_branch = 0 THEN ptd.qty ELSE 0 END) AS transferred, tbl1.purchased-SUM(CASE WHEN tbl1.batch_number = ptd.batch_number AND t.from_branch = 0 THEN ptd.qty ELSE 0 END) AS balance_qty FROM (SELECT p.id, pd.product, p.product_name, pd.batch_number, SUM(pd.qty) AS purchased FROM purchase_details pd LEFT JOIN products p ON p.id = pd.product WHERE p.id = ? GROUP BY pd.batch_number) AS tbl1 LEFT JOIN product_transfer_details ptd ON ptd.product = tbl1.id LEFT JOIN product_transfers t ON t.id = ptd.transfer_id GROUP BY tbl1.batch_number", [$request->product]);
        else :
            /*$inventory = DB::select("SELECT tbl4.batch_number, tbl4.balance-(tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty) AS balance_qty FROM (SELECT tbl3.*, IFNULL(SUM(CASE WHEN tbl3.batch_number = po.batch_number AND pm.branch = ? AND pm.bill_generated = 1 THEN po.qty END), 0) AS surgery_qty FROM (SELECT tbl2.*, IFNULL(SUM(CASE WHEN tbl2.batch_number = p.batch_number AND ph.branch = ? THEN p.qty END), 0) AS pharmaqty FROM (SELECT tbl1.*, IFNULL(SUM(CASE WHEN tbl1.batch_number = m.batch_number AND pmr.branch = ? AND m.status = 1 THEN m.qty END), 0) AS medqty FROM (SELECT tblTrnsf.*, tblTrnsf.received-tblTrnsf.transferred AS balance FROM (SELECT pd.product, pd.batch_number, IFNULL(SUM(CASE WHEN pt.to_branch = ? THEN pd.qty END), 0) AS received, IFNULL(SUM(CASE WHEN pt.from_branch = ? THEN pd.qty END), 0) AS transferred FROM product_transfer_details pd LEFT JOIN product_transfers pt ON pd.transfer_id = pt.id GROUP BY pd.batch_number) AS tblTrnsf) AS tbl1 LEFT JOIN patient_medicine_records m ON m.medicine = tbl1.product LEFT JOIN patient_medical_records pmr ON pmr.id = m.medical_record_id GROUP BY tbl1.batch_number) AS tbl2 LEFT JOIN pharmacy_records p ON p.product = tbl2.product LEFT JOIN pharmacies ph ON ph.id = p.pharmacy_id GROUP BY tbl2.batch_number) AS tbl3 LEFT JOIN post_operative_medicine_details po ON po.product = tbl3.product LEFT JOIN post_operative_medicines pm ON pm.id = po.pom_id GROUP BY tbl3.batch_number) AS tbl4 WHERE tbl4.product = ? HAVING balance_qty > 0", [$request->branch, $request->branch, $request->branch, $request->branch, $request->branch, $request->product]);*/
            $inventory =  DB::select("SELECT tbl4.received AS purchased, tbl4.transferred+tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty AS transferred, tbl4.product_name, tbl4.product, tbl4.batch_number, tbl4.balance-(tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty) AS balance_qty FROM (SELECT tbl3.*, IFNULL(SUM(CASE WHEN tbl3.batch_number = po.batch_number AND pm.branch = ? AND pm.bill_generated = 1 THEN po.qty END), 0) AS surgery_qty FROM (SELECT tbl2.*, IFNULL(SUM(CASE WHEN tbl2.batch_number = p.batch_number AND ph.branch = ? THEN p.qty END), 0) AS pharmaqty FROM (SELECT tbl1.*, IFNULL(SUM(CASE WHEN tbl1.batch_number = m.batch_number AND pmr.branch = ? AND m.status = 1 THEN m.qty END), 0) AS medqty FROM (SELECT tblTrnsf.*, tblTrnsf.received-tblTrnsf.transferred AS balance FROM (SELECT pr.product_name, pd.product, pd.batch_number, IFNULL(SUM(CASE WHEN pt.to_branch = ? AND pt.approved = 1 THEN pd.qty END), 0) AS received, IFNULL(SUM(CASE WHEN pt.from_branch = ? AND pt.approved = 1 THEN pd.qty END), 0) AS transferred FROM product_transfer_details pd LEFT JOIN product_transfers pt ON pd.transfer_id = pt.id LEFT JOIN products pr ON pr.id = pd.product GROUP BY pd.batch_number) AS tblTrnsf) AS tbl1 LEFT JOIN patient_medicine_records m ON m.medicine = tbl1.product LEFT JOIN patient_medical_records pmr ON pmr.id = m.medical_record_id GROUP BY tbl1.batch_number) AS tbl2 LEFT JOIN pharmacy_records p ON p.product = tbl2.product LEFT JOIN pharmacies ph ON ph.id = p.pharmacy_id GROUP BY tbl2.batch_number) AS tbl3 LEFT JOIN post_operative_medicine_details po ON po.product = tbl3.product LEFT JOIN post_operative_medicines pm ON pm.id = po.pom_id GROUP BY tbl3.batch_number) AS tbl4 WHERE tbl4.product = ? HAVING balance_qty > 0", [$request->branch, $request->branch, $request->branch, $request->branch, $request->branch, $request->product]);
        endif;
        $op = "<option value=''>Select</option>";
        if ($inventory) :
            foreach ($inventory as $key => $inv) :
                $op .= "<option value='" . $inv->batch_number . "'>" . $inv->batch_number . "  (" . $inv->balance_qty . " Qty in Hand)</option>";
            endforeach;
        else :
            $op .= "<option value='NRF'>No records found</option>";
        endif;
        echo $op;
    }
    public static function getProductForTransferForEdit($product, $branch)
    {
        if ($branch == 0) :
            /*$inventory = DB::select("SELECT tbl1.product, tbl1.batch_number AS batch_number, tbl1.purchased AS purchased, SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS transferred, tbl1.purchased-SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS balance_qty FROM (SELECT p.id, p.product, SUM(p.qty) AS purchased, p.batch_number FROM purchase_details p WHERE p.product = ? GROUP BY p.batch_number) AS tbl1 LEFT JOIN product_transfer_details t ON tbl1.product = t.product LEFT JOIN product_transfers pt ON pt.id=t.transfer_id GROUP BY tbl1.id HAVING balance_qty > 0", [$product]);*/
            $inventory = DB::select("SELECT tbl1.id as product, tbl1.product_name, tbl1.batch_number, tbl1.purchased, SUM(CASE WHEN tbl1.batch_number = ptd.batch_number AND t.from_branch = 0 THEN ptd.qty ELSE 0 END) AS transferred, tbl1.purchased-SUM(CASE WHEN tbl1.batch_number = ptd.batch_number AND t.from_branch = 0 THEN ptd.qty ELSE 0 END) AS balance_qty FROM (SELECT p.id, p.product_name, pd.batch_number, SUM(pd.qty) AS purchased FROM purchase_details pd LEFT JOIN products p ON p.id = pd.product WHERE p.id = ? GROUP BY pd.batch_number) AS tbl1 LEFT JOIN product_transfer_details ptd ON ptd.product = tbl1.id LEFT JOIN product_transfers t ON t.id = ptd.transfer_id GROUP BY tbl1.batch_number", [$product]);
        else :
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
    public function getProductPrice(Request $request)
    {
        $price = DB::table('purchase_details')->where('batch_number', $request->batch_number)->where('product', $request->product)->first();
        $addition = $request->addition ?? 0;
        $mrp = $price->mrp;
        $rate = $price->price + $addition;
        $taxa = ($rate * $price->tax_percentage) / 100;
        return response()->json([
            'mrp' => $mrp,
            'taxp' => $price->tax_percentage,
            'price' => $rate - $taxa,
            'discount' => $mrp - $rate,
            'taxa' => $taxa,
            'total' => $rate * $request->qty,
        ]);
    }
    public function getDayBookDetailed(Request $request)
    {
        $html = "";
        $fdate = Carbon::createFromFormat('d/M/Y', $request->fdate)->startOfDay();
        $tdate = Carbon::createFromFormat('d/M/Y', $request->tdate)->endOfDay();
        $branch = $request->branch;
        if ($request->type == 'consultation') :
            $html = $this->getConsultationDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'consultationDiscount') :
            $html = $this->getConsultationDiscountDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'procedure') :
            $html = $this->getProcedureDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'procedureDiscount') :
            $html = $this->getProcedureDiscountDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'certificate') :
            $html = $this->getCertificateDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'pharmacy') : // Direct
            $html = $this->getPharmacyDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'medicine') :
            $html = $this->getMedicineDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'vision') :
            $html = $this->getVisionDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'incomecash') :
            $html = $this->getIncomeCashDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'incomeupi') :
            $html = $this->getIncomeUpiDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'incomecard') :
            $html = $this->getIncomeCardDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'incomepending') :
            $html = $this->getIncomePendingDetailed($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'outstandingReceived') :
            $html = $this->getOutstandingReceived($fdate, $tdate, $branch);
        endif;
        if ($request->type == 'outstanding') :
            $html = $this->getPatientOutstandingDueDetails($fdate, $tdate, $branch);
        endif;
        echo $html;
    }

    public function getPatientOutstandingDueDetails($fdate, $tdate, $branch)
    {
        $outstandings = Helper::getPatientOutstanding(null, null, $branch);
        $tot = 0;
        $duetot = 0;
        $paidtot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Patient</th><th>PId</th><th>Due</th><th>Received</th><th>Balance</th></tr></thead><tbody>";
        foreach ($outstandings as $key => $os) :
            $html .= "<tr>";
            $html .= "<td>" . $key + 1 . "</td>";
            $html .= "<td>" . $os['patient_name'] . "</td>";
            $html .= "<td>" . $os['patient_id'] . "</td>";
            $html .= "<td class='text-end'>" . number_format($os['due'], 2) . "</td>";
            $html .= "<td class='text-end'>" . number_format($os['received'], 2) . "</td>";
            $html .= "<td class='text-end'>" . number_format($os['balance'], 2) . "</td>";
            $html .= "</tr>";
            $tot += $os['balance'];
            $duetot += $os['due'];
            $paidtot += $os['received'];
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='3' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($duetot, 2) . "</td><td class='text-end fw-bold'>" . number_format($paidtot, 2) . "</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }

    public function getOutstandingReceived($fdate, $tdate, $branch)
    {
        $income = DB::table('patient_payments as pp')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 'pp.medical_record_id')->leftJoin('patient_registrations as pr', 'pr.id', '=', 'pmr.patient_id')->selectRaw("pp.medical_record_id, pr.patient_name, pr.patient_id, DATE_FORMAT(pp.created_at, '%d/%b/%Y') AS cdate, pp.amount")->where('pp.branch', $branch)->whereBetween('pp.created_at', [$fdate, $tdate])->where('pp.type', 9)->where('pp.payment_mode', '!=', 1)->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($income as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->medical_record_id . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->amount . "</td>";
            $html .= "</tr>";
            $tot += $record->amount;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    public function getInventoryDetailed(Request $request)
    {
        $html = "";
        $product = $request->product;
        $batch = $request->batch;
        $branch = $request->branch;
        if ($request->type == 'stockin') :
            $html = $this->getStockInDetailed($product, $batch, $branch);
        endif;
        if ($request->type == 'stockout') :
            $html = $this->getStockOutDetailed($product, $batch, $branch);
        endif;
        echo $html;
    }
    public function getStockOutDetailed($product, $batch, $branch)
    {
        $outs = DB::table('product_transfer_details as pd')->leftJoin('product_transfers as pt', 'pt.id', '=', 'pd.transfer_id')->leftJoin('products as pr', 'pr.id', '=', 'pd.product')->leftJoin('branches as b', 'b.id', '=', 'to_branch')->selectRaw("'Transfer' AS type, pd.qty, pd.batch_number, pr.product_name, DATE_FORMAT(pt.transfer_date, '%d/%b/%Y') AS pdate, b.branch_name")->when($branch == 0, function ($query) {
            return $query->where('pt.from_branch', 0);
        })->when($branch > 0, function ($query) use ($branch) {
            return $query->where('pt.from_branch', $branch);
        })->where('pd.product', $product)->where('pd.batch_number', $batch)->orderBy('pt.transfer_date');

        $meds = DB::table("patient_medicine_records as m")->leftjoin('patient_medical_records as pmr', 'pmr.id', '=', 'm.medical_record_id')->leftJoin('products as pr', 'pr.id', '=', 'm.medicine')->selectRaw("'Medicine' AS type, m.qty, m.batch_number, pr.product_name, DATE_FORMAT(pmr.created_at, '%d/%b/%Y') AS pdate, m.medical_record_id AS branch_name")->where('pmr.branch', $branch)->where('m.medicine', $product)->where('m.batch_number', $batch)->unionAll($outs);

        $pharmacy = DB::table("pharmacy_records as pr")->leftjoin('pharmacies as p', 'p.id', '=', 'pr.pharmacy_id')->leftJoin('products as pro', 'pro.id', '=', 'pr.product')->selectRaw("'Med Out' AS type, pr.qty, pr.batch_number, pro.product_name, DATE_FORMAT(p.created_at, '%d/%b/%Y') AS pdate, p.patient_name AS branch_name")->where('p.branch', $branch)->where('pr.product', $product)->where('pr.batch_number', $batch)->unionAll($meds);

        $surgery = DB::table("post_operative_medicine_details as pd")->leftJoin("post_operative_medicines as pm", 'pm.id', '=', 'pd.pom_id')->leftJoin('products as pro', 'pro.id', '=', 'pd.product')->selectRaw("pm.type, pd.qty, pd.batch_number, pro.product_name, DATE_FORMAT(pm.created_at, '%d/%b/%Y') AS pdate, pm.medical_record_id AS branch_name")->where('pm.branch', $branch)->where('pd.product', $product)->where('pd.batch_number', $batch)->where('pm.bill_generated', 1)->unionAll($pharmacy)->get();

        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Product</th><th>Batch Number</th><th>Transfer Date</th><th>Trans. To</th><th>Type</th><th>Qty</th></tr></thead><tbody>";
        $c = 1;
        foreach ($surgery as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->product_name . "</td>";
            $html .= "<td>" . $record->batch_number . "</td>";
            $html .= "<td>" . $record->pdate . "</td>";
            $html .= "<td>" . $record->branch_name . "</td>";
            $html .= "<td>" . $record->type . "</td>";
            $html .= "<td class='text-end'>" . $record->qty . "</td>";
            $html .= "</tr>";
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='6' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . $surgery->sum('qty') . "</td></tr></tfoot></table>";
        return $html;
    }
    public function getStockInDetailed($product, $batch, $branch)
    {
        if ($branch == 0) :
            $ins = DB::table('purchase_details as pd')->leftJoin('purchases as p', 'p.id', '=', 'pd.purchase_id')->leftJoin('products as pr', 'pr.id', '=', 'pd.product')->selectRaw("pd.qty, pd.batch_number, pr.product_name, DATE_FORMAT(p.delivery_date, '%d/%b/%Y') AS pdate")->where('pd.product', $product)->where('pd.batch_number', $batch)->orderBy('p.delivery_date')->get();
        else :
            $ins = DB::table('product_transfer_details as pd')->leftJoin('product_transfers as pt', 'pt.id', '=', 'pd.transfer_id')->leftJoin('products as pr', 'pr.id', '=', 'pd.product')->selectRaw("pd.qty, pd.batch_number, pr.product_name, DATE_FORMAT(pt.transfer_date, '%d/%b/%Y') AS pdate")->where('pt.to_branch', $branch)->where('pd.product', $product)->where('pd.batch_number', $batch)->orderBy('pt.transfer_date')->get();
        endif;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Product</th><th>Batch Number</th><th>Purchase Date</th><th>Qty</th></tr></thead><tbody>";
        $c = 1;
        foreach ($ins as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->product_name . "</td>";
            $html .= "<td>" . $record->batch_number . "</td>";
            $html .= "<td>" . $record->pdate . "</td>";
            $html .= "<td class='text-end'>" . $record->qty . "</td>";
            $html .= "</tr>";
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='4' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . $ins->sum('qty') . "</td></tr></tfoot></table>";
        return $html;
    }

    private function getConsultationDetailed($fdate, $tdate, $branch)
    {
        $consultation = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'pr.patient_id')->select('preg.patient_name', 'preg.patient_id', 'pmr.id as mrid', 'pr.doctor_fee AS fee', 'pr.discount AS discount', DB::raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('pr.created_at', [$fdate, $tdate])->where('pr.branch', $branch)->where('pr.status', 1)->orderByDesc('pmr.id')->havingRaw('fee > ?', [0])->get();
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Reg.Date</th><th>Amount</th></tr></thead><tbody>";
        $c = 1;
        $tot = 0;
        foreach ($consultation as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->rdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee - $record->discount . "</td>";
            $html .= "</tr>";
            $tot += $record->fee - $record->discount;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getConsultationDiscountDetailed($fdate, $tdate, $branch)
    {
        $consultation = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'pr.patient_id')->select('preg.patient_name', 'preg.patient_id', 'pmr.id as mrid', 'pr.doctor_fee AS fee', 'pr.discount', 'pr.discount_notes as notes', 'pr.rc_number', DB::raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('pr.created_at', [$fdate, $tdate])->where('pr.branch', $branch)->where('pr.status', 1)->orderByDesc('pmr.id')->havingRaw('discount > ?', [0])->get();
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Reg.Date</th><th>Notes</th><th>Discount</th></tr></thead><tbody>";
        $c = 1;
        $tot = 0;
        foreach ($consultation as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->rdate . "</td>";
            $html .= "<td>" . $record->notes . ' | Vehicle Card: ' . $record->rc_number . "</td>";
            $html .= "<td class='text-end'>" . $record->discount . "</td>";
            $html .= "</tr>";
            $tot += $record->discount;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='6' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getProcedureDetailed($fdate, $tdate, $branch)
    {
        $procedure = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 'pp.medical_record_id')->leftJoin('patient_registrations as pr', 'pr.id', '=', 'pmr.patient_id')->leftJoin('users as u', 'pp.created_by', 'u.id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id as mrid', 'pr.patient_name', 'pr.patient_id', DB::raw("SUM(pp.fee) as fee, DATE_FORMAT(pp.created_at, '%d/%b/%Y') AS cdate, u.name"))->whereBetween('pp.created_at', [$fdate, $tdate])->where('pp.branch', $branch)->whereNull('pp.deleted_at')->groupBy('pp.medical_record_id')->orderByDesc('pmr.id')->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Procedures</th><th>Date</th><th>Created By</th></th><th>Amount</th></tr></thead><tbody>";
        foreach ($procedure as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->procs . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td>" . $record->name . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='7' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getProcedureDiscountDetailed($fdate, $tdate, $branch)
    {
        $procedure = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 'pp.medical_record_id')->leftJoin('patient_registrations as pr', 'pr.id', '=', 'pmr.patient_id')->leftJoin('patient_references as pref', 'pp.medical_record_id', 'pref.id')->leftJoin('users as u', 'pp.created_by', 'u.id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id as mrid', 'pr.patient_name', 'pr.patient_id', 'pref.discount_notes as notes', 'pp.discount_category', DB::raw("SUM(pp.discount) as discount, DATE_FORMAT(pp.created_at, '%d/%b/%Y') AS cdate, u.name"))->whereBetween('pp.created_at', [$fdate, $tdate])->where('pp.branch', $branch)->whereNull('pp.deleted_at')->groupBy('pp.medical_record_id')->orderByDesc('pmr.id')->havingRaw('discount > ?', [0])->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Procedures</th></th><th>Notes</th><th>Discount</th></tr></thead><tbody>";
        foreach ($procedure as $key => $record) :
            $notes = ($record->notes) ? $record->notes : $record->discount_category;
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->procs . "</td>";
            $html .= "<td>" . $notes . "</td>";
            $html .= "<td class='text-end'>" . $record->discount . "</td>";
            $html .= "</tr>";
            $tot += $record->discount;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='6' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getCertificateDetailed($fdate, $tdate, $branch)
    {
        $certificate = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->leftJoin('patient_references as pr', 'pr.id', '=', 'pc.medical_record_id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'pr.patient_id')->select('preg.patient_name', 'preg.patient_id', 'pr.id as mrid', DB::raw("SUM(pcd.fee) AS fee, DATE_FORMAT(pc.created_at, '%d/%b/%Y') AS cdate"),)->whereBetween('pc.created_at', [$fdate, $tdate])->where('pc.branch_id', $branch)->where('pcd.status', 'I')->groupBy('pcd.patient_certificate_id')->orderByDesc('pc.medical_record_id')->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($certificate as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getPharmacyDetailed($fdate, $tdate, $branch)
    {
        $pharmacy = DB::table('pharmacy_records as pr')->leftJoin('pharmacies as p', 'p.id', '=', 'pr.pharmacy_id')->whereIn('p.used_for', ['Customer', 'B2B'])->select('p.id', 'p.patient_name', 'p.other_info', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate, SUM(pr.total) AS fee"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->groupBy('pr.pharmacy_id')->orderBy('p.patient_name', 'asc')->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>Bill No.</th><th>Patient Name</th><th>Address</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($pharmacy as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->id . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->other_info . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getMedicineDetailed($fdate, $tdate, $branch)
    {
        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->leftJoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.id as mrid', DB::raw("SUM(m.total) AS fee, DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('m.status', 1)->whereBetween('p.created_at', [$fdate, $tdate])->where('p.branch', $branch)->groupBy('m.medical_record_id')->orderByDesc('m.medical_record_id')->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($medicine as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getVisionDetailed($fdate, $tdate, $branch)
    {
        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->leftJoin('patient_registrations as p', 'm.patient_id', '=', 'p.id')->where('m.branch', $branch)->where('s.fee', '>', 0)->whereBetween('s.created_at', [$fdate, $tdate])->select('p.patient_name', 'p.patient_id', 'm.id as mrid', 's.fee', DB::raw("DATE_FORMAT(s.created_at, '%d/%b/%Y') AS cdate"))->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($vision as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getIncomeCashDetailed($fdate, $tdate, $branch)
    {
        $income = DB::table('patient_payments as p')->leftjoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.medical_record_id as mrid', 'p.amount as fee', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->where('p.payment_mode', 1)->where('p.type', '!=', 9)->where('p.type', '!=', 8)->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($income as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getIncomeUpiDetailed($fdate, $tdate, $branch)
    {
        $income = DB::table('patient_payments as p')->leftjoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.medical_record_id as mrid', 'p.amount as fee', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->whereIn('p.payment_mode', [3, 4])->where('p.type', '!=', 9)->where('p.type', '!=', 8)->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($income as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getIncomeCardDetailed($fdate, $tdate, $branch)
    {
        $income = DB::table('patient_payments as p')->leftjoin('patient_registrations as preg', 'preg.id', '=', 'p.patient_id')->select('preg.patient_name', 'preg.patient_id', 'p.medical_record_id as mrid', 'p.amount as fee', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->whereIn('p.payment_mode', [2, 5, 7])->where('p.type', '!=', 9)->where('p.type', '!=', 8)->get();
        $c = 1;
        $tot = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Date</th><th>Amount</th></tr></thead><tbody>";
        foreach ($income as $key => $record) :
            $html .= "<tr>";
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $record->mrid . "</td>";
            $html .= "<td>" . $record->patient_name . "</td>";
            $html .= "<td>" . $record->patient_id . "</td>";
            $html .= "<td>" . $record->cdate . "</td>";
            $html .= "<td class='text-end'>" . $record->fee . "</td>";
            $html .= "</tr>";
            $tot += $record->fee;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='5' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($tot, 2) . "</td></tr></tfoot></table>";
        return $html;
    }
    private function getIncomePendingDetailed($fdate, $tdate, $branch)
    {
        $records = DB::table('patient_medical_records as p')->leftJoin('patient_registrations as preg', 'p.patient_id', '=', 'preg.id')->where('p.branch', $branch)->whereBetween('p.created_at', [$fdate, $tdate])->select('p.id as mrid', 'preg.patient_name', 'preg.patient_id')->get();
        $c = 1;
        $owed_tot = 0;
        $paid_tot = 0;
        $tot = 0;
        $cls = '';
        $outstanding = 0;
        $html = "<table class='table table-bordered table-striped table-hover table-sm'><thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Owed</th><th>Paid</th><th>Due</th></tr></thead><tbody>";
        foreach ($records as $row) :
            $html .= "<tr>";
            $owed = $this->getOwedTotal($row->mrid);
            $paid = $this->getPaidTotal($row->mrid);
            $outstanding += PatientPayment::where('medical_record_id', $row->mrid)->where('type', 8)->sum('amount');
            $cls = ($owed - $paid > 0) ? 'text-danger' : '';
            $html .= "<td>" . $c++ . "</td>";
            $html .= "<td>" . $row->mrid . "</td>";
            $html .= "<td>" . $row->patient_name . "</td>";
            $html .= "<td>" . $row->patient_id . "</td>";
            $html .= "<td class='text-end'>" . number_format($owed, 2) . "</td>";
            $html .= "<td class='text-end'>" . number_format($paid, 2) . "</td>";
            $html .= "<td class='text-end " . $cls . "'>" . number_format($owed - $paid, 2) . "</td>";
            $html .= "</tr>";
            $paid_tot += $paid;
            $owed_tot += $owed;
            $tot += $owed - $paid;
        endforeach;
        $html .= "</tbody><tfoot><tr><td colspan='4' class='fw-bold text-end'>Total</td><td class='text-end fw-bold'>" . number_format($owed_tot, 2) . "</td><td class='text-end fw-bold'>" . number_format($paid_tot + $outstanding, 2) . "</td><td class='text-end fw-bold " . $cls . "'>" . number_format($tot - $outstanding, 2) . "</td></tr></tfoot>";
        $html .= "<tfoot><tr><td class='text-end fw-bold' colspan='6'>Outstanding Due</td><td class='fw-bold text-end'>" . number_format($outstanding, 2) . "</td></tr></tfoot>";
        return $html;
    }
    public function getOwedTotal($mrid)
    {
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->where('pmr.id', $mrid)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $mrid)->where('pr.status', 1)->sum(DB::raw("pr.doctor_fee-pr.discount"));

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.medical_record_id', $mrid)->whereNull('pp.deleted_at')->sum('fee');

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
    public function getPaidTotal($mrid)
    {
        $paid = DB::table('patient_payments as p')->where('p.medical_record_id', $mrid)->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');
        return $paid;
    }
    public function getPreviousDues($patient_id)
    {
        $mrns = DB::table('patient_references')->where('patient_id', $patient_id)->orderByDesc('id')->get();
        $owed = 0.00;
        $paid = DB::table('patient_payments')->where('type', '!=', 8)->where('patient_id', $patient_id)->sum('amount');
        foreach ($mrns as $key => $val) :
            $owed += $this->getOwedTotal($val->id);
        endforeach;
        return $owed - $paid;
    }
    public function certificateAuthentication($id)
    {
        $patient = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->select('pr.patient_name', 'pr.age', 'pmr.doctor_id', 'pmr.branch', 'pr.address')->where('pmr.id', $id)->first();
        $doctor = DB::table('doctors')->find($patient->doctor_id);
        $branch = DB::table('branches')->find($patient->branch);
        $certificate = DB::table('patient_certificates as pc')->where('pc.medical_record_id', $id)->first();

        $certs = DB::table('patient_certificate_details as pcd')->leftJoin('certificate_types as ct', 'pcd.certificate_type', '=', 'ct.id')->leftJoin('patient_certificates as pc', 'pc.id', '=', 'pcd.patient_certificate_id')->select(DB::raw("(GROUP_CONCAT(ct.name SEPARATOR ', ')) as certs"))->where('pc.medical_record_id', $id)->where('pcd.status', 'I')->value('certs');

        $details = DB::table('patient_certificate_details as pcd')->select(DB::raw("DATE_FORMAT(pcd.created_at, '%d/%b/%Y %h:%i %p') AS created_at"))->where('pcd.patient_certificate_id', $certificate->id)->where('pcd.status', 'I')->first();
        return view('authentication.certificate', compact('certificate', 'details', 'patient', 'doctor', 'branch', 'certs'));
    }

    public function getlabtests(Request $request)
    {
        $sid = $request->sid;
        $op = "";
        $tests = DB::table('lab_types')->selectRaw("id, lab_type_name, tested_from, CASE WHEN tested_from = 0 THEN 'Outside Laboratory' ELSE 'Own Laboratory' END AS lab, order_by")->where('surgery_type', $sid)->orderBy('order_by')->get();
        if ($tests->isNotEmpty()) :
            foreach ($tests as $key => $test) :
                $op .= "<div class='row mt-3'><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2' data-placeholder='Select' name='test_id[]' required='required'><option value='" . $test->id . "'>" . $test->lab_type_name . "</option></select></div><div class='col-sm-3'><input type='text' name='notes[]' class='form-control' placeholder='Notes' /></div><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2' data-placeholder='Select' name='tested_from[]' required='required'><option value='" . $test->tested_from . "'>" . $test->lab . "</option></select></div><div class='col-sm-2'><input type='number' name='order_by[]' class='form-control' value='" . $test->order_by . "' /></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>";
            endforeach;
        else :
            $op = "";
        endif;
        echo $op;
    }

    public function updatesmsstatus(Request $request)
    {
        $mrid = $request->mrid;
        $chk = $request->chk;
        DB::table('patient_references')->where('id', $mrid)->update(['sms' => $chk]);
    }

    public function getsurgeryconsumables(Request $request)
    {
        $sid = $request->sid;
        $op = "";
        $consumables = SurgeryConsumableItem::where('surgery_id', $sid)->get();
        if ($consumables->isNotEmpty()) :
            foreach ($consumables as $key => $co) :
                $op .= "<div class='row mt-3'><div class='col-sm-3'><select class='form-control form-control-md show-tick ms select2' data-placeholder='Select' name='consumable_id[]' required='required'><option value='" . $co->consumable_id . "'>" . $co->consumable->name . "</option></select></div><div class='col-sm-2'><input type='number' name='qty[]' class='form-control' value='" . $co->default_qty . "' placeholder='Qty'/></div><div class='col-sm-1'><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></div></div>";
            endforeach;
        endif;
        echo $op;
    }

    public function switchBranch($branch)
    {
        if (UserBranch::where('user_id', Auth::id())->where('branch_id', decrypt($branch))->exists()) :
            Session::put('branch', decrypt($branch));
            return redirect()->back()->with("success", "Branch switched successfully");
        else :
            return redirect()->back()->with("error", "Requested branch access denied!");
        endif;
    }

    public function waDocs(Request $request)
    {

        $this->validate($request, [
            'mobile_number' => 'required|numeric|digits:10',
        ]);
        $mr = PatientMedicalRecord::findOrFail($request->mrid);
        try {

            if ($request->medical_record):
                $res = Helper::sendRequestedDocviaWa($request->mobile_number, $request->patient_name, $mr->id, 'mrecord');
                DocumentTrack::create([
                    'patient_id' => $mr->patient_id,
                    'created_by' => $request->user()->id,
                    'sent_to' => $request->mobile_number,
                    'sent_via' => 'wa',
                    'doc_type' => 'mrecord',
                    'msg_id' => $res['messages'][0]['id'],
                    'msg_status' => $res['messages'][0]['message_status'],
                ]);
            endif;
            if ($request->patient_history):
                $res = Helper::sendRequestedDocviaWa($request->mobile_number, $request->patient_name, $mr->patient_id, 'phistory');
                DocumentTrack::create([
                    'patient_id' => $mr->patient_id,
                    'created_by' => $request->user()->id,
                    'sent_to' => $request->mobile_number,
                    'sent_via' => 'wa',
                    'doc_type' => 'phistory',
                    'msg_id' => $res['messages'][0]['id'],
                    'msg_status' => $res['messages'][0]['message_status'],
                ]);
            endif;
            if ($request->spectacle_prescription):
                $res = Helper::sendRequestedDocviaWa($request->mobile_number, $request->patient_name, $mr->id, 'spectacle');
                DocumentTrack::create([
                    'patient_id' => $mr->patient_id,
                    'created_by' => $request->user()->id,
                    'sent_to' => $request->mobile_number,
                    'sent_via' => 'wa',
                    'doc_type' => 'spectacle',
                    'msg_id' => $res['messages'][0]['id'],
                    'msg_status' => $res['messages'][0]['message_status'],
                ]);
            endif;
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->back()->with("success", "Documents sent successfully");
    }

    public function emailDocs(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        try {
            $id = $request->mrid;
            $record = PatientMedicalRecord::findOrFail($id);
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
            $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->leftJoin('product_categories as t', 'p.medicine_type', 't.id')->select('p.product_name', 'm.qty', 'm.dosage', 'm.duration', 'm.notes', 't.name', DB::raw("CASE WHEN m.eye='L' THEN 'Left Eye Only' WHEN m.eye='R' THEN 'Right Eye Only' WHEN m.eye='B' THEN 'Both Eyes' ELSE 'Oral' END AS eye"))->where('m.medical_record_id', $id)->get();
            //$patient = DB::table('patient_registrations')->find($record->patient_id);
            $patient = PatientRegistrations::find($record->patient_id);
            $reference = DB::table('patient_references')->find($record->mrn);
            $doctor = DB::table('doctors')->find($record->doctor_id);
            $branch = DB::table('branches')->find($reference->branch);
            $sympt = explode(',', $record->symptoms);
            $diag = explode(',', $record->diagnosis);
            $symptoms = DB::table('symptoms')->whereIn('id', $sympt)->get();
            $diagnosis = DB::table('diagnosis')->whereIn('id', $diag)->get();
            $spectacle = Spectacle::where('medical_record_id', $id)->first();
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
            $qrcode = base64_encode(QrCode::format('svg')->size(75)->errorCorrection('H')->generate('https://play.google.com/store/apps/details?id=com.devieh.virtualtoken'));
            $data = ['body' => $request->body, 'cname' => $request->patient_name, 'uname' => Auth::user()->name, 'time' => Carbon::now(), 'is_mrecord' => $request->medical_record, 'is_phistory' => $request->patient_history, 'is_spectacle' => $request->spectacle_prescription];
            $data['mrecord'] = Pdf::loadView('pdf.medical-record', compact('record', 'patient', 'doctor', 'qrcode', 'branch', 'reference', 'symptoms', 'diagnosis', 'medicines', 'spectacle', 'tonometry', 'keratometry', 'ascan', 'pachymetry', 'onotes', 'retina_od', 'retina_os', 'v_od_1', 'v_os_1', 'v_od_2', 'v_os_2', 'v_od_3', 'v_os_3', 'v_od_4', 'v_os_4', 'sel_1_od', 'sel_1_os', 'sel_2_od', 'sel_2_os', 'sel_3_od', 'sel_3_os', 'sel_4_od', 'sel_4_os', 'sel_5_od', 'sel_5_os', 'sel_6_od', 'sel_6_os', 'sel_7_od', 'sel_7_os', 'sel_8_od', 'sel_8_os', 'sel_9_od', 'sel_9_os', 'sel_10_od', 'sel_10_os', 'sel_11_od', 'sel_11_os', 'sel_12_od', 'sel_12_os', 'sel_13_od', 'sel_13_os', 'sel_14_od', 'sel_14_os', 'sel_15_od', 'sel_15_os', 'sel_16_od', 'sel_16_os', 'sel_17_od', 'sel_17_os', 'sel_18_od', 'sel_18_os', 'sel_19_od', 'sel_19_os', 'sel_20_od', 'sel_20_os'));
            $references = DB::table('patient_references')->where('patient_id', $patient->id)->get();
            $mrecord = $record;
            $mrecords = DB::table('patient_medical_records')->where('patient_id', $patient->id)->get();
            $onote = OperationNote::where('patient_id', $patient->id);
            $data['phistory'] = Pdf::loadView('pdf.patient-history', compact('mrecords', 'qrcode', 'patient', 'onote'));
            if ($spectacle):
                $data['spectacle'] = Pdf::loadView('pdf.spectacle-prescription', compact('patient', 'qrcode', 'reference', 'spectacle', 'mrecord', 'doctor', 'branch'));
            endif;
            //Mail::to($request->email)->bcc('vijoysasidharan@yahoo.com')->send(new SendDocuments($data));
            Mail::send('email.send-documents', $data, function ($message) use ($data, $request, $patient, $spectacle) {
                $message->to($request->email, $request->email)->bcc('cssumesh@yahoo.com')
                    ->subject("Devi Eye Hospitals - Documents");
                if ($data['is_mrecord']):
                    $message->attachData($data['mrecord']->output(), "medical_record.pdf");
                    DocumentTrack::create([
                        'patient_id' => $patient->id,
                        'created_by' => $request->user()->id,
                        'sent_to' => $request->email,
                        'sent_via' => 'email',
                        'doc_type' => 'mrecord',
                    ]);
                endif;
                if ($data['is_phistory']):
                    $message->attachData($data['phistory']->output(), "history.pdf");
                    DocumentTrack::create([
                        'patient_id' => $patient->id,
                        'created_by' => $request->user()->id,
                        'sent_to' => $request->email,
                        'sent_via' => 'email',
                        'doc_type' => 'phistory',
                    ]);
                endif;
                if ($data['is_spectacle'] && $spectacle):
                    $message->attachData($data['spectacle']->output(), "spectacle.pdf");
                    DocumentTrack::create([
                        'patient_id' => $patient->id,
                        'created_by' => $request->user()->id,
                        'sent_to' => $request->email,
                        'sent_via' => 'email',
                        'doc_type' => 'spectacle',
                    ]);
                endif;
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->back()->with("success", "Documents sent successfully");
    }

    function iolPower()
    {
        return view('extras.iol-power');
    }

    function calculateIolPower(Request $request)
    {
        $this->validate($request, [
            'axial_length' => 'required',
        ]);
        $ar = self::getIolPower($request->axial_length, $request->k1, $request->k2, $request->acd, $request->a_constant, $request->formula, $request->target_refraction, $request->a0, $request->a1, $request->a2, $request->lens_thick, $request->wtw, $request->age);
        return response()->json([
            'iol_Power' => $ar[0] . ' D',
            'status' => 'success',
            "message" => $ar[1],
            "type" => $request->type,
        ]);
    }

    function getIolPower($axl, $k1, $k2, $acd, $a, $formula, $r, $a0, $a1, $a2, $lens_thickness, $wtw, $age)
    {
        $p = 0;
        $msg = "Applied formula is as requested";
        $k = ($k1 > 0 || $k2 > 0) ? ($k1 + $k2) / 2 : 0;
        if ($formula == 0):
            if ($axl < 21):
                // Very short eye - High hyperopia, more IOL power needed - Formual 1
                $p = self::calculateFormula1($axl, $k, $acd, $a);
                $msg = "Very short eye - High hyperopia, more IOL power needed. Applied formula is Hoffer Q";
            endif;
            if ($axl >= 21 && $axl < 22):
                // Short eye - Mild hyperopia - Formula 1
                $p = self::calculateFormula1($axl, $k, $acd, $a);
                $msg = "Short eye - Mild hyperopia. Applied formula is Hoffer Q";
            endif;
            if ($axl >= 22 && $axl < 24.5):
                // Normal range - Most common in emmetropic eyes - Formula 3 and 4
                $p = self::calculateFormula3($axl, $k, $acd, $a, $r, $lens_thickness, $wtw, $age);
                $msg = "Normal range - Most common in emmetropic eyes. Holladay 2";
            endif;
            if ($axl >= 24.5 && $axl <= 26):
                // Moderately long - Often mild myopia - Formula 4
                $p = self::calculateFormula4($axl, $k, $acd, $a);
                $msg = "Normal range - Most common in emmetropic eyes. Applied formula is SRK/T";
            endif;
            if ($axl > 26):
                // Long eye - High myopia, special IOL calculation needed - Formula 5, 4 and 2
                $p = self::calculateFormula5($axl, $k, $acd, $a);
                $msg = "Long eye - High myopia, special IOL calculation needed. Applied formula is Barrett Universal II";
            endif;
        else:
            if ($formula == 1)
                $p = self::calculateFormula1($axl, $k, $acd, $a);
            if ($formula == 2)
                $p = self::calculateFormula2($axl, $k, $acd, $r, $a0, $a1, $a2);
        endif;
        return array($p, $msg);
    }

    function calculateFormula1($axl, $k, $acd, $a)
    {
        // Hoffer Q
        $n = 1.336; // Refractive index of aqueous/vitreous
        $elp = 0.56 * $acd + 0.36 * $k - 0.1;
        $retina_adjustment = 0.65696 - 0.02029 * $axl;
        $l = $axl + $retina_adjustment;
        $iol_power = ($n / ($l - $elp)) - ($n / $axl);
        $iol_power *= 1000;
        return round($iol_power, 2);
    }

    function calculateFormula2($axl, $k, $acd, $r, $a0, $a1, $a2)
    {
        // Haigis
        $n = 1.336;
        $d = 0; // Refractive index of aqueous/vitreous
        $elp = $a0 + ($a1 * $acd) + ($a2 * $axl);
        $iol_power = ($n / ($axl - $elp)) - ($n / ($axl - $d)) - $k;
        return round($iol_power, 2);
    }

    function calculateFormula3($axl, $k, $acd, $a, $r, $lens_thickness, $wtw, $age)
    {
        // Holladay 2
        $n = 1.336;
        $elp = 0.56 * $acd + 0.28 * $lens_thickness + 0.1 * $wtw - 0.05 * $age + 1.5;
        $iol_power = ($n / ($axl - $elp)) - ($n / ($axl - 0.05)); // assuming 0.05mm offset to retina
        $iol_power *= 1000;
        return round($iol_power, 2);
    }

    function calculateFormula4($axl, $k, $acd, $a)
    {
        // SRK/T
        $elp = 0.56 * $acd + 0.36 * $k - 0.1;
        $p = $a - 2.5 * $axl - 0.9 * $k;
    }

    function calculateFormula5($axl, $k, $acd, $a)
    {
        // Barrett Universal II
    }

    function calculateFormula6($axl, $k, $acd, $a)
    {
        // Kane
    }
}
