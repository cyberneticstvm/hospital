<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\doctor;
use App\Models\HFA;
use App\Models\IncomeExpenseHead as Head;
use App\Models\LoginLog;
use App\Models\PatientMedicalRecord;
use App\Models\PatientProcedure;
use App\Models\PatientReference;
use App\Models\PatientRegistrations;
use App\Models\PatientSurgeryConsumable;
use App\Models\Procedure;
use App\Models\ProcedureType;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\RoyaltyCard;
use App\Models\Spectacle;
use App\Models\Surgery;
use App\Models\TestsAdvised;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    private $branch;

    function __construct()
    {
        $this->middleware('permission:report-daybook-show|report-daybook-fetch|report-income-expense-show|report-income-expense-fetch|report-patient-payments-show|report-patient-payments-fetch|report-active-users-show|report-loginlog-show|report-loginlog-fetch|report-appointment-show|report-appointment-fetch|report-patient-show|report-patient-fetch', ['only' => ['showdaybook', 'fetchdaybook', 'showincomeexpense', 'fetchincomeexpense', 'showpayment', 'fetchpayment', 'activeusers', 'showloginlog', 'fetchloginlog', 'showappointment', 'fetchappointment', 'showpatient', 'fetchpatient']]);
        $this->middleware('permission:report-daybook-show', ['only' => ['showdaybook', 'showdaybookcc']]);
        $this->middleware('permission:report-daybook-fetch', ['only' => ['fetchdaybook', 'fetchdaybookcc']]);
        $this->middleware('permission:report-income-expense-show', ['only' => ['showincomeexpense']]);
        $this->middleware('permission:report-income-expense-fetch', ['only' => ['fetchincomeexpense']]);
        $this->middleware('permission:report-patient-payments-show', ['only' => ['showpayment']]);
        $this->middleware('permission:report-patient-payments-fetch', ['only' => ['fetchpayment']]);
        $this->middleware('permission:report-active-users-show', ['only' => ['activeusers']]);
        $this->middleware('permission:report-loginlog-show', ['only' => ['showloginlog']]);
        $this->middleware('permission:report-loginlog-fetch', ['only' => ['fetchloginlog']]);
        $this->middleware('permission:report-appointment-show', ['only' => ['showappointment']]);
        $this->middleware('permission:report-appointment-fetch', ['only' => ['fetchappointment']]);
        $this->middleware('permission:report-patient-show', ['only' => ['showpatient']]);
        $this->middleware('permission:report-patient-fetch', ['only' => ['fetchpatient']]);

        $this->middleware('permission:report-medical-record-show', ['only' => ['showmRecord']]);
        $this->middleware('permission:report-medical-record-fetch', ['only' => ['fetchmRecord']]);
        $this->middleware('permission:report-surgery-show', ['only' => ['showSurgery']]);
        $this->middleware('permission:report-surgery-fetch', ['only' => ['fetchSurgery']]);
        $this->middleware('permission:report-postop-show', ['only' => ['showPostOp']]);
        $this->middleware('permission:report-postop-fetch', ['only' => ['fetchPostOp']]);
        $this->middleware('permission:report-tests-advised-show', ['only' => ['showtAdvised']]);
        $this->middleware('permission:report-tests-advised-fetch', ['only' => ['fetchtAdvised']]);
        $this->middleware('permission:report-hfa-show', ['only' => ['showHfa']]);
        $this->middleware('permission:report-hfa-fetch', ['only' => ['fetchHfa']]);
        $this->middleware('permission:report-tests-procedure', ['only' => ['showTests', 'fetchTests']]);
        $this->middleware('permission:report-glasses-prescribed', ['only' => ['glassesPrescribed', 'fetchGlassesPrescribed']]);
        $this->middleware('permission:report-rc-card-usage', ['only' => ['showDiscount', 'fetchDiscount']]);
        $this->middleware('permission:report-procedure-cancelled', ['only' => ['procedureCancelled', 'fetchProcedureCancelled']]);

        $this->branch = session()->get('branch');
    }

    private function getBranches($branch)
    {
        if (Auth::user()->roles->first()->name == 'Admin') :
            $branches = Branch::all();
        else :
            $branches = Branch::where('id', $branch)->get();
        endif;
        return $branches;
    }

    private function isAdmin()
    {
        if (Auth::user()->roles->first()->name == 'Admin') :
            return true;
        endif;
        return false;
    }
    private function isAccounts()
    {
        if (Auth::user()->roles->first()->name == 'Accounts') :
            return true;
        endif;
        return false;
    }
    private function isCEO()
    {
        if (Auth::user()->roles->first()->name == 'CEO') :
            return true;
        endif;
        return false;
    }

    public function showdaybook()
    {
        $branches = $this->getBranches($this->branch);
        $is_admin = $this->isAdmin();
        $is_accounts = $this->isAccounts();
        $isCEO = $this->isCEO();
        $records = [];
        $inputs = [];
        $reg_fee_total = 0.00;
        $consultation_fee_total = 0.00;
        $procedure_fee_total = 0.00;
        $certificate_fee_total = 0.00;
        $pharmacy = 0.00;
        $medicine = 0.00;
        $income = 0.00;
        $expense = 0.00;
        $income_total = 0.00;
        $income_received_cash = 0.00;
        $income_received_upi = 0.00;
        $income_received_card = 0.00;
        $income_received_staff = 0.00;
        $opening_balance = 0.00;
        $vision = 0.00;
        $outstanding = 0.00;
        $clinical_lab = 0.00;
        $radiology_lab = 0.00;
        $surgery_medicine = 0.00;
        $postop_medicine = 0.00;
        $surgery_consumables = 0.00;
        $outstanding_received = 0.00;
        $outstanding_received_other = 0.00;
        $patientOutStandingTotal = 0.00;
        return view('reports.daybook', compact('inputs', 'records', 'branches', 'reg_fee_total', 'consultation_fee_total', 'procedure_fee_total', 'certificate_fee_total', 'pharmacy', 'medicine', 'income', 'expense', 'income_total', 'income_received_cash', 'income_received_upi', 'income_received_card', 'income_received_staff', 'opening_balance', 'vision', 'is_admin', 'is_accounts', 'isCEO', 'outstanding', 'outstanding_received', 'clinical_lab', 'radiology_lab', 'surgery_medicine', 'postop_medicine', 'surgery_consumables', 'outstanding_received_other', 'patientOutStandingTotal'));
    }
    public function fetchdaybook(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $is_admin = $this->isAdmin();
        $is_accounts = $this->isAccounts();
        $isCEO = $this->isCEO();
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $prev_day = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay()->subDays(1);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();

        $opening_balance = DB::table('daily_closing as d')->select(DB::raw("MAX(d.id), IFNULL(d.closing_balance, 0) AS closing_balance"))->whereDate('d.date', '=', $prev_day)->where('d.branch', $request->branch)->orderByDesc('d.id')->first()->closing_balance;

        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pr.branch', $request->branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->whereBetween('pr.created_at', [$startDate, $endDate])->where('pr.branch', $request->branch)->where('pr.status', 1)->sum(DB::raw("pr.doctor_fee-pr.discount"));

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $request->branch)->whereNull('pp.deleted_at')->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->whereBetween('pc.created_at', [$startDate, $endDate])->where('pc.branch_id', $request->branch)->where('pcd.status', 'I')->sum('pcd.fee');

        $pharmacy = 25.00; //DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $request->branch)->where('p.used_for', 'Customer')->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $request->branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->where('m.branch', $request->branch)->whereBetween('s.created_at', [$startDate, $endDate])->sum('s.fee');

        $clinical_lab = DB::table('lab_clinics as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $request->branch)->sum('l.fee');

        $radiology_lab = DB::table('lab_radiologies as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $request->branch)->sum('l.fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $request->branch)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $request->branch)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::whereBetween('created_at', [$startDate, $endDate])->where('branch', $request->branch)->sum('total_after_discount');

        $income = DB::table('incomes')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $income_received_upi = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [3, 4])->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $income_received_card = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [2, 5, 7])->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $income_received_staff = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [6])->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $outstanding = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 8)->sum('amount');

        $outstanding_received = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 9)->where('payment_mode', 1)->sum('amount');

        $outstanding_received_other = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 9)->where('payment_mode', '!=', 1)->sum('amount');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $income + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;

        return view('reports.daybook', compact('inputs', 'branches', 'reg_fee_total', 'consultation_fee_total', 'procedure_fee_total', 'certificate_fee_total', 'pharmacy', 'medicine', 'income', 'expense', 'income_total', 'income_received_cash', 'income_received_upi', 'income_received_card', 'income_received_staff', 'opening_balance', 'vision', 'is_admin', 'is_accounts', 'isCEO', 'outstanding', 'outstanding_received', 'clinical_lab', 'radiology_lab', 'surgery_medicine', 'postop_medicine', 'surgery_consumables', 'outstanding_received_other'));
    }
    public function showdaybookcc()
    {
        $branches = $this->getBranches($this->branch);
        $is_admin = $this->isAdmin();
        $is_accounts = $this->isAccounts();
        $isCEO = $this->isCEO();
        $records = [];
        $inputs = [];
        $reg_fee_total = 0.00;
        $consultation_fee_total = 0.00;
        $consultation_fee_discount = 0.00;
        $procedure_fee_total = 0.00;
        $procedure_fee_discount = 0.00;
        $certificate_fee_total = 0.00;
        $pharmacy = 0.00;
        $medicine = 0.00;
        $income = 0.00;
        $expense = 0.00;
        $income_total = 0.00;
        $income_received_cash = 0.00;
        $income_received_upi = 0.00;
        $income_received_card = 0.00;
        $income_received_staff = 0.00;
        $opening_balance = 0.00;
        $vision = 0.00;
        $outstanding = 0.00;
        $clinical_lab = 0.00;
        $radiology_lab = 0.00;
        $surgery_medicine = 0.00;
        $postop_medicine = 0.00;
        $surgery_consumables = 0.00;
        $surgery_consumables_discount = 0.00;
        $outstanding_received = 0.00;
        $outstanding_received_other = 0.00;
        return view('reports.daybookcc', compact('inputs', 'records', 'branches', 'reg_fee_total', 'consultation_fee_total', 'consultation_fee_discount', 'procedure_fee_total', 'procedure_fee_discount', 'certificate_fee_total', 'pharmacy', 'medicine', 'income', 'expense', 'income_total', 'income_received_cash', 'income_received_upi', 'income_received_card', 'income_received_staff', 'opening_balance', 'vision', 'is_admin', 'is_accounts', 'isCEO', 'outstanding', 'outstanding_received', 'clinical_lab', 'radiology_lab', 'surgery_medicine', 'postop_medicine', 'surgery_consumables', 'surgery_consumables_discount', 'outstanding_received_other'));
    }
    public function fetchdaybookcc(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $is_admin = $this->isAdmin();
        $is_accounts = $this->isAccounts();
        $isCEO = $this->isCEO();
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $prev_day = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay()->subDays(1);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();

        $opening_balance = DB::table('daily_closing as d')->select(DB::raw("MAX(d.id), IFNULL(d.closing_balance, 0) AS closing_balance"))->whereDate('d.date', '=', $prev_day)->where('d.branch', $request->branch)->orderByDesc('d.id')->first()->closing_balance;

        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pr.branch', $request->branch)->sum('pr.registration_fee');

        $consultation_fee = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->whereBetween('pr.created_at', [$startDate, $endDate])->where('pr.branch', $request->branch)->where('pr.status', 1);

        $consultation_fee_total = $consultation_fee->sum('pr.doctor_fee');
        $consultation_fee_discount = $consultation_fee->sum('pr.discount');

        $procedure_fee = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $request->branch)->whereNull('pp.deleted_at');

        $procedure_fee_total = $procedure_fee->sum('fee') + $procedure_fee->sum('discount');
        $procedure_fee_discount = $procedure_fee->sum('discount');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->whereBetween('pc.created_at', [$startDate, $endDate])->where('pc.branch_id', $request->branch)->where('pcd.status', 'I')->sum('pcd.fee');

        $pharmacy = DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $request->branch)->where('p.used_for', 'Customer')->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $request->branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->where('m.branch', $request->branch)->whereBetween('s.created_at', [$startDate, $endDate])->sum('s.fee');

        $clinical_lab = DB::table('lab_clinics as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $request->branch)->sum('l.fee');

        $radiology_lab = DB::table('lab_radiologies as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $request->branch)->sum('l.fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $request->branch)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $request->branch)->sum('d.total');

        $surgery_consumable = PatientSurgeryConsumable::whereBetween('created_at', [$startDate, $endDate])->where('branch', $request->branch);

        $surgery_consumables = $surgery_consumable->sum('total');
        $surgery_consumables_discount = $surgery_consumable->sum('discount');

        $income = DB::table('incomes')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $income_received_upi = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [3, 4])->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $income_received_card = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [2, 5, 7])->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $income_received_staff = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [6])->where('type', '!=', 9)->where('type', '!=', 8)->sum('amount');

        $outstanding = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 8)->sum('amount');

        $outstanding_received = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 9)->where('payment_mode', 1)->sum('amount');

        $outstanding_received_other = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 9)->where('payment_mode', '!=', 1)->sum('amount');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $income + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;

        return view('reports.daybookcc', compact('inputs', 'branches', 'reg_fee_total', 'consultation_fee_total', 'consultation_fee_discount', 'procedure_fee_total', 'procedure_fee_discount', 'certificate_fee_total', 'pharmacy', 'medicine', 'income', 'expense', 'income_total', 'income_received_cash', 'income_received_upi', 'income_received_card', 'income_received_staff', 'opening_balance', 'vision', 'is_admin', 'is_accounts', 'isCEO', 'outstanding', 'outstanding_received', 'clinical_lab', 'radiology_lab', 'surgery_medicine', 'postop_medicine', 'surgery_consumables', 'surgery_consumables_discount', 'outstanding_received_other'));
    }
    public function showincomeexpense()
    {
        $branches = $this->getBranches($this->branch);
        $heads = Head::all();
        $records = [];
        $inputs = [];
        return view('reports.income-expense', compact('branches', 'records', 'inputs', 'heads'));
    }
    public function fetchincomeexpense(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
            'type' => 'required'
        ]);
        $branches = $this->getBranches($this->branch);
        $heads = Head::all();
        $inputs = array($request->fromdate, $request->todate, $request->branch, $request->type, $request->head);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        if ($request->type == 'I') :
            $records = DB::table('incomes as i')->leftJoin('income_expense_heads as h', 'i.head', '=', 'h.id')->leftJoin('branches as b', 'i.branch', '=', 'b.id')->select('i.id', DB::raw("DATE_FORMAT(i.created_at, '%d/%b/%Y') AS cdate"), 'i.description', 'i.amount', DB::raw("'Income' AS type"), 'b.branch_name', 'h.name')->whereBetween('i.created_at', [$startDate, $endDate])->when($request->head > 0, function ($query) use ($request) {
                return $query->where('i.head', $request->head);
            })->where('i.branch', $request->branch)->orderBy('i.created_at')->get();
        else :
            $records = DB::table('expenses as e')->leftJoin('income_expense_heads as h', 'e.head', '=', 'h.id')->leftJoin('branches as b', 'e.branch', '=', 'b.id')->select('e.id', DB::raw("DATE_FORMAT(e.created_at, '%d/%b/%Y') AS cdate"), 'e.description', 'e.amount', DB::raw("'Expense' AS type"), 'b.branch_name', 'h.name')->whereBetween('e.created_at', [$startDate, $endDate])->when($request->head > 0, function ($query) use ($request) {
                return $query->where('e.head', $request->head);
            })->where('e.branch', $request->branch)->orderBy('e.created_at')->get();
        endif;
        return view('reports.income-expense', compact('branches', 'records', 'inputs', 'heads'));
    }

    public function showpayment()
    {
        if ($this->isAdmin()) :
            $branches = DB::table('branches')->get();
        else :
            $branches = $this->getBranches($this->branch);
        endif;
        $records = [];
        $inputs = [];
        return view('reports.patient-payments', compact('branches', 'records', 'inputs'));
    }
    public function fetchpayment(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        if ($this->isAdmin()) :
            $branches = DB::table('branches')->get();
        else :
            $branches = $this->getBranches($this->branch);
        endif;
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        $records = DB::table('patient_payments as pp')->leftJoin('patient_registrations as pr', 'pp.patient_id', '=', 'pr.id')->leftJoin('branches as b', 'b.id', 'pp.branch')->leftJoin('users as u', 'u.id', '=', 'pp.created_by')->selectRaw("pr.patient_name, pr.patient_id, pp.medical_record_id, b.branch_name, u.name as uname, DATE_FORMAT(pp.created_at, '%d/%b/%Y %h:%i %p') AS pdate, pp.amount")->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $request->branch)->orderByDesc('pp.created_at')->get();
        return view('reports.patient-payments', compact('branches', 'records', 'inputs'));
    }

    public function activeusers()
    {
        $users = User::whereNotNull('session_id')->get();
        return view('reports.active-users', compact('users'));
    }

    public function showloginlog()
    {
        $users = User::all();
        $records = [];
        $inputs = [];
        return view('reports.login-log', compact('users', 'records', 'inputs'));
    }

    public function fetchloginlog(Request $request)
    {
        $users = User::all();
        $inputs = array($request->fromdate, $request->todate, $request->user);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        $records = LoginLog::whereBetween('logged_in', [$startDate, $endDate])->when($request->user > 0, function ($query) use ($request) {
            return $query->where('user_id', $request->user);
        })->get();
        return view('reports.login-log', compact('users', 'records', 'inputs'));
    }

    public function showappointment()
    {
        $branches = $this->getBranches($this->branch);
        $doctors = doctor::all();
        $records = [];
        $inputs = [];
        return view('reports.appointment', compact('branches', 'records', 'inputs', 'doctors'));
    }
    public function fetchappointment(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $doctors = doctor::all();
        $inputs = array($request->fromdate, $request->todate, $request->status, $request->branch, $request->doctor);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $records = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->when($request->doctor > 0, function ($query) use ($request) {
            return $query->where('doctor', $request->doctor);
        })->when($request->branch > 0, function ($query) use ($request) {
            return $query->where('branch', $request->branch);
        })->when($request->status <> 0, function ($query) use ($request) {
            return $query->where('medical_record_id', $request->status, 0);
        })->get();
        return view('reports.appointment', compact('branches', 'records', 'inputs', 'doctors'));
    }

    public function showpatient()
    {
        $branches = $this->getBranches($this->branch);
        $records = [];
        $inputs = [];
        return view('reports.patient', compact('branches', 'records', 'inputs'));
    }
    public function fetchpatient(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $records = PatientRegistrations::whereBetween('created_at', [$startDate, $endDate])->when($request->branch > 0, function ($query) use ($request) {
            return $query->where('branch', $request->branch);
        })->get();
        return view('reports.patient', compact('branches', 'records', 'inputs'));
    }

    public function surgeryPayments()
    {
        $branches = $this->getBranches($this->branch);
        $records = [];
        $inputs = [];
        return view('reports.surgery-payments', compact('branches', 'records', 'inputs'));
    }

    public function fetchSurgeryPayments(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        $records = PatientSurgeryConsumable::with('patient')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->latest()->get();
        return view('reports.surgery-payments', compact('branches', 'records', 'inputs'));
    }

    public function pharmacy()
    {
        $inputs = array(date('d/M/Y'), date('d/M/Y'), '', $this->branch);
        $branches = $this->getBranches($this->branch);
        $products = Product::orderBy('product_name')->get();
        $records = collect();
        return view('reports.pharmacy', compact('inputs', 'branches', 'records', 'products'));
    }

    public function fetchPharmacy(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $products = Product::orderBy('product_name')->get();
        $inputs = array($request->fromdate, $request->todate, $request->product, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        $records = DB::table('patient_medicine_records as pmr')->leftJoin('patient_medical_records as pmr1', 'pmr.medical_record_id', '=', 'pmr1.id')->leftJoin('patient_registrations as p', 'p.id', '=', 'pmr1.patient_id')->leftJoin('doctors as doc', 'pmr1.doctor_id', '=', 'doc.id')->whereBetween('pmr.updated_at', [$startDate, $endDate])->where('pmr.branch_id', $request->branch)->when($request->product > 0, function ($q) use ($request) {
            return $q->where("pmr.medicine", $request->product);
        })->selectRaw("pmr.id, pmr.medical_record_id, pmr.status, p.patient_name, p.patient_id, doc.doctor_name, SUM(pmr.total) AS total")->groupBy('pmr.medical_record_id')->orderByDesc('pmr.id')->get();
        return view('reports.pharmacy', compact('branches', 'records', 'inputs', 'products'));
    }

    public function showmRecord()
    {
        $branches = $this->getBranches($this->branch);
        $records = [];
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch);
        return view('reports.mrecord', compact('branches', 'records', 'inputs'));
    }

    public function fetchmRecord(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->from_date, $request->to_date, $request->branch);
        $records = PatientMedicalRecord::whereBetween('created_at', [$startDate, $endDate])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('branch', $request->branch);
        })->get();
        return view('reports.mrecord', compact('branches', 'records', 'inputs'));
    }

    public function showSurgery()
    {
        $branches = $this->getBranches($this->branch);
        $records = [];
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch);
        return view('reports.surgery', compact('branches', 'records', 'inputs'));
    }

    public function fetchSurgery(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->from_date, $request->to_date, $request->branch);
        $records = Surgery::leftJoin('patient_medical_records as pmr', 'surgeries.medical_record_id', 'pmr.id')->select('surgeries.id', 'surgeries.doctor_id', 'surgeries.patient_id', 'surgeries.branch', 'surgeries.surgery_type', 'pmr.created_at')->whereBetween('pmr.created_at', [$startDate, $endDate])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('surgeries.branch', $request->branch);
        })->get();
        return view('reports.surgery', compact('branches', 'records', 'inputs'));
    }

    public function showPostOp()
    {
        $branches = $this->getBranches($this->branch);
        $records = [];
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch);
        return view('reports.postop', compact('branches', 'records', 'inputs'));
    }

    public function fetchPostOp(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->from_date, $request->to_date, $request->branch);
        $records = Surgery::leftJoin('patient_medical_records as pmr', 'surgeries.medical_record_id', 'pmr.id')->select('surgeries.id', 'surgeries.doctor_id', 'surgeries.patient_id', 'surgeries.branch', 'surgeries.surgery_type', 'surgeries.remarks', 'surgeries.surgeon', 'surgeries.surgery_date')->whereBetween('surgeries.updated_at', [$startDate, $endDate])->whereIn('surgeries.status', [4])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('surgeries.branch', $request->branch);
        })->get();
        return view('reports.postop', compact('branches', 'records', 'inputs'));
    }

    public function showtAdvised()
    {
        $branches = $this->getBranches($this->branch);
        $records = [];
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch, '');
        return view('reports.tests-advised', compact('branches', 'records', 'inputs'));
    }

    public function fetchtAdvised(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->from_date, $request->to_date, $request->branch, $request->status);
        $records = TestsAdvised::whereBetween('created_at', [$startDate, $endDate])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('branch', $request->branch);
        })->when($request->status, function ($q) use ($request) {
            return $q->where('status', $request->status);
        })->get();
        return view('reports.tests-advised', compact('branches', 'records', 'inputs'));
    }

    public function showHfa()
    {
        $branches = $this->getBranches($this->branch);
        $status = DB::table('types')->where('category', 'surgery')->get();
        $records = [];
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch, 4);
        return view('reports.hfa', compact('branches', 'records', 'inputs', 'status'));
    }

    public function fetchHfa(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $startDate = Carbon::parse($request->from_date)->startOfDay();
        $endDate = Carbon::parse($request->to_date)->endOfDay();
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->from_date, $request->to_date, $request->branch, $request->status);
        $status = DB::table('types')->where('category', 'surgery')->get();
        $records = HFA::whereBetween('created_at', [$startDate, $endDate])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('branch', $request->branch);
        })->when($request->status > 0, function ($q) use ($request) {
            return $q->where('status', $request->status);
        })->get();
        return view('reports.hfa', compact('branches', 'records', 'inputs', 'status'));
    }

    public function showTests()
    {
        $branches = $this->getBranches($this->branch);
        $procs = Procedure::orderBy('name')->get();
        $records = [];
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch, 0);
        return view('reports.tests', compact('branches', 'records', 'inputs', 'procs'));
    }

    public function fetchTests(Request $request)
    {
        $branches = $this->getBranches($this->branch);
        $procs = Procedure::orderBy('name')->get();
        $inputs = array($request->from_date, $request->to_date, $request->branch, $request->procedure);
        $records = PatientProcedure::whereBetween('created_at', [Carbon::parse($request->from_date)->startOfDay(), Carbon::parse($request->to_date)->endOfDay()])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('branch', $request->branch);
        })->when($request->procedure > 0, function ($q) use ($request) {
            return $q->where('procedure', $request->procedure);
        })->get();
        return view('reports.tests', compact('branches', 'records', 'inputs', 'procs'));
    }

    public function glassesPrescribed()
    {
        $branches = $this->getBranches($this->branch);
        $records = collect();
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch, 'yes');
        return view('reports.glasses-prescribed', compact('branches', 'records', 'inputs'));
    }

    public function fetchGlassesPrescribed(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $inputs = array($request->fromdate, $request->todate, $request->branch, $request->status);
        $branches = $this->getBranches($this->branch);
        $records = Spectacle::leftJoin('patient_references as p', 'p.id', 'spectacles.medical_record_id')->whereBetween('spectacles.created_at', [Carbon::parse($request->fromdate)->startOfDay(), Carbon::parse($request->todate)->endOfDay()])->where('p.branch', $request->branch)->selectRaw("spectacles.id, spectacles.patient_id, spectacles.medical_record_id, spectacles.created_at")->when($request->status != 'all', function ($q) use ($request) {
            return $q->where('spectacles.glasses_prescribed', $request->status);
        })->get();
        return view('reports.glasses-prescribed', compact('branches', 'records', 'inputs'));
    }

    public function procedureCancelled()
    {
        $branches = $this->getBranches($this->branch);
        $records = collect();
        $procs = ProcedureType::all();
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $procs->first()->id, $this->branch);
        return view('reports.proc-cancelled', compact('branches', 'records', 'inputs', 'procs'));
    }

    public function fetchProcedureCancelled(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'branch' => 'required',
            'procedure' => 'required',
        ]);
        $branches = $this->getBranches($this->branch);
        $procs = ProcedureType::all();
        /*$tbl = $procs->where('id', $request->procedure)->first()->table_name;
        $className = 'App\\Models\\' . Str::studly(Str::singular($tbl));
        $model = new $className;*/
        $records = PatientProcedure::onlyTrashed()->where('branch', $request->branch)->where('type', $procs->where('id', $request->procedure)->first()->type)->whereBetween('deleted_at', [Carbon::parse($request->from_date)->startOfDay(), Carbon::parse($request->to_date)->endOfDay()])->get();
        $inputs = array($request->from_date, $request->to_date, $request->procedure, $request->branch);
        return view('reports.proc-cancelled', compact('branches', 'records', 'inputs', 'procs'));
    }

    public function transfer()
    {
        $branches = Branch::pluck("branch_name", "id");
        $products = Product::pluck("product_name", "id");
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch, NULL);
        $transfers = collect();
        return view('reports.transfer', compact('branches', 'products', 'inputs', 'transfers'));
    }

    public function fetchTransfer(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'branch' => 'required',
        ]);
        $branches = Branch::pluck("branch_name", "id");
        $products = Product::pluck("product_name", "id");
        $inputs = array($request->from_date, $request->to_date, $request->branch, $request->product);
        $transfers = ProductTransfer::whereBetween('transfer_date', [Carbon::parse($request->from_date)->startOfDay(), Carbon::parse($request->to_date)->endOfDay()])->when($request->branch > 0, function ($q) use ($request) {
            return $q->where('from_branch', $request->branch);
        })->when($request->product > 0, function ($q) use ($request) {
            return $q->leftJoin('product_transfer_details AS ptd', 'ptd.transfer_id', 'product_transfers.id')->where('ptd.product', $request->product);
        })->get();
        return view('reports.transfer', compact('branches', 'products', 'inputs', 'transfers'));
    }

    public function showDiscount()
    {
        $rcs = RoyaltyCard::all();
        $branches = $this->getBranches($this->branch);
        $inputs = array(date('Y-m-d'), date('Y-m-d'), $this->branch, 0, 0);
        $records = collect();
        return view('reports.rc-card-usage', compact('branches', 'records', 'inputs', 'rcs'));
    }

    public function fetchDiscount(Request $request)
    {
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
            'category' => 'required',
            'rc' => 'required',
        ]);
        if ($request->category == 1):
            $records = PatientReference::where('rc_type', $request->rc)->where('branch', $request->branch)->where('status', 1)->whereBetween('created_at', [Carbon::parse($request->fromdate)->startOfDay(), Carbon::parse($request->todate)->endOfDay()])->selectRaw("patient_references.id, patient_references.branch, patient_references.patient_id, patient_references.rc_number, doctor_fee AS fee, discount, 0 AS mrid, patient_references.created_at")->get();
        else:
            $records = PatientReference::leftJoin('patient_medical_records AS pmr', 'pmr.mrn', 'patient_references.id')->leftJoin('patient_procedures AS pp', 'pmr.id', 'pp.medical_record_id')->where('patient_references.rc_type', $request->rc)->where('patient_references.branch', $request->branch)->where('patient_references.status', 1)->whereBetween('patient_references.created_at', [Carbon::parse($request->fromdate)->startOfDay(), Carbon::parse($request->todate)->endOfDay()])->select('patient_references.id', 'patient_references.branch', 'patient_references.patient_id', 'patient_references.rc_number', 'pp.fee', 'pp.discount', 'pmr.id AS mrid', 'patient_references.created_at')->get();
        endif;
        $rcs = RoyaltyCard::all();
        $branches = $this->getBranches($this->branch);
        $inputs = array($request->fromdate, $request->todate, $request->branch, $request->rc, $request->category);
        return view('reports.rc-card-usage', compact('branches', 'records', 'inputs', 'rcs'));
    }

    public function getClosingBalance($branch)
    {

        $prev_day = Carbon::today()->subDays(1);
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $opening_balance = DB::table('daily_closing as d')->select(DB::raw("MAX(d.id), IFNULL(d.closing_balance, 0) AS closing_balance"))->whereDate('d.date', '=', $prev_day)->where('d.branch', $branch)->orderByDesc('d.id')->first()->closing_balance;

        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pref.id', 'pmr.mrn')->where('pref.review', 'no')->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pr.branch', $branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->whereBetween('pr.created_at', [$startDate, $endDate])->where('pr.branch', $branch)->where('pr.status', 1)->sum(DB::raw("pr.doctor_fee-pr.discount"));

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $branch)->whereNull('pp.deleted_at')->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->whereBetween('pc.created_at', [$startDate, $endDate])->where('pc.branch_id', $branch)->where('pcd.status', 'I')->sum('pcd.fee');

        $pharmacy = DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $branch)->where('p.used_for', 'Customer')->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->where('m.branch', $branch)->whereBetween('s.created_at', [$startDate, $endDate])->sum('s.fee');

        $income = DB::table('incomes')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->where('type', '!=', 9)->sum('amount');

        $income_received_other = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [2, 3, 4, 5, 7, 10])->where('type', '!=', 9)->sum('amount');

        $clinical_lab = DB::table('lab_clinics as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $branch)->sum('l.fee');

        $radiology_lab = DB::table('lab_radiologies as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $branch)->sum('l.fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $branch)->sum('d.total');

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $branch)->sum('d.total');

        $surgery_consumables = PatientSurgeryConsumable::whereBetween('created_at', [$startDate, $endDate])->where('branch', $branch)->sum('total_after_discount');

        $outstanding_received = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('type', 9)->sum('amount');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $income + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;

        $closing_balance = $income_total - ($income_received_other + $outstanding_received + $expense);

        return view('test', compact('closing_balance'));
    }
}
