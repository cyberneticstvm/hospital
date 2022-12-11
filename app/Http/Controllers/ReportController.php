<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\IncomeExpenseHead as Head;

use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    private $branch;

    function __construct()
    {
        $this->middleware('permission:report-daybook-show|report-daybook-fetch|report-income-expense-show|report-income-expense-fetch', ['only' => ['showdaybook','fetchdaybook','showincomeexpense','fetchincomeexpense']]);
        $this->middleware('permission:report-daybook-show', ['only' => ['showdaybook']]);
        $this->middleware('permission:report-daybook-fetch', ['only' => ['fetchdaybook']]);
        $this->middleware('permission:report-income-expense-show', ['only' => ['showincomeexpense']]);
        $this->middleware('permission:report-income-expense-fetch', ['only' => ['fetchincomeexpense']]);

        $this->branch = session()->get('branch');
    }

    private function getBranches($branch){
        if(Auth::user()->roles->first()->name == 'Admin'):
            $branches = Branch::all();
        else:
            $branches = Branch::where('id', $branch)->get();
        endif;
        return $branches;
    }

    private function isAdmin(){
        if(Auth::user()->roles->first()->name == 'Admin'):
            return true;
        endif;
        return false;
    }
    private function isAccounts(){
        if(Auth::user()->roles->first()->name == 'Accounts'):
            return true;
        endif;
        return false;
    }
    private function isCEO(){
        if(Auth::user()->roles->first()->name == 'CEO'):
            return true;
        endif;
        return false;
    }

    public function showdaybook(){
        $branches = $this->getBranches($this->branch);
        $is_admin = $this->isAdmin(); $is_accounts = $this->isAccounts(); $isCEO = $this->isCEO();
        $records = []; $inputs = []; $reg_fee_total = 0.00; $consultation_fee_total = 0.00; $procedure_fee_total = 0.00; $certificate_fee_total = 0.00; $pharmacy = 0.00; $medicine = 0.00; $income = 0.00; $expense = 0.00; $income_total = 0.00; $income_received_cash = 0.00; $income_received_upi = 0.00; $income_received_card = 0.00; $income_received_staff = 0.00; $opening_balance = 0.00; $vision = 0.00;
        return view('reports.daybook', compact('inputs', 'records', 'branches', 'reg_fee_total', 'consultation_fee_total', 'procedure_fee_total', 'certificate_fee_total', 'pharmacy', 'medicine', 'income', 'expense', 'income_total', 'income_received_cash', 'income_received_upi', 'income_received_card', 'income_received_staff', 'opening_balance', 'vision', 'is_admin', 'is_accounts', 'isCEO'));
    }
    public function fetchdaybook(Request $request){
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $branches = $this->getBranches($this->branch); 
        $is_admin = $this->isAdmin(); $is_accounts = $this->isAccounts(); $isCEO = $this->isCEO();
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $prev_day = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay()->subDays(1);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();

        $opening_balance = DB::table('daily_closing as d')->select(DB::raw("MAX(d.id), IFNULL(d.closing_balance, 0) AS closing_balance"))->whereDate('d.date', '=', $prev_day)->where('d.branch', $request->branch)->orderByDesc('d.id')->first()->closing_balance;

        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pr.branch', $request->branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->whereBetween('pr.created_at', [$startDate, $endDate])->where('pr.branch', $request->branch)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $request->branch)->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->whereBetween('pc.created_at', [$startDate, $endDate])->where('pc.branch_id', $request->branch)->where('pcd.status', 'I')->sum('pcd.fee');        

        $pharmacy = DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $request->branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $request->branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->where('m.branch', $request->branch)->whereBetween('s.created_at', [$startDate, $endDate])->sum('s.fee');

        $income = DB::table('incomes')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $request->branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->sum('amount');

        $income_received_upi = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [3,4])->sum('amount');

        $income_received_card = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [2,5,7])->sum('amount');

        $income_received_staff = DB::table('patient_payments')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [6])->sum('amount');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $income;

        return view('reports.daybook', compact('inputs', 'branches', 'reg_fee_total', 'consultation_fee_total', 'procedure_fee_total', 'certificate_fee_total', 'pharmacy', 'medicine', 'income', 'expense', 'income_total', 'income_received_cash', 'income_received_upi', 'income_received_card', 'income_received_staff', 'opening_balance', 'vision', 'is_admin', 'is_accounts', 'isCEO'));
    }
    public function showincomeexpense(){
        $branches = $this->getBranches($this->branch);
        $heads = Head::all();
        $records = []; $inputs = []; 
        return view('reports.income-expense', compact('branches', 'records', 'inputs', 'heads'));
    }
    public function fetchincomeexpense(Request $request){
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
        if($request->type == 'I'):
            $records = DB::table('incomes as i')->leftJoin('income_expense_heads as h', 'i.head', '=', 'h.id')->leftJoin('branches as b', 'i.branch', '=', 'b.id')->select('i.id', DB::raw("DATE_FORMAT(i.created_at, '%d/%b/%Y') AS cdate"), 'i.description', 'i.amount', DB::raw("'Income' AS type"), 'b.branch_name', 'h.name')->whereBetween('i.created_at', [$startDate, $endDate])->when($request->head > 0, function($query) use ($request){
                return $query->where('i.head', $request->head);
            })->where('i.branch', $request->branch)->orderBy('i.created_at')->get();
        else:
            $records = DB::table('expenses as e')->leftJoin('income_expense_heads as h', 'e.head', '=', 'h.id')->leftJoin('branches as b', 'e.branch', '=', 'b.id')->select('e.id', DB::raw("DATE_FORMAT(e.created_at, '%d/%b/%Y') AS cdate"), 'e.description', 'e.amount', DB::raw("'Expense' AS type"), 'b.branch_name', 'h.name')->whereBetween('e.created_at', [$startDate, $endDate])->when($request->head > 0, function($query) use ($request){
                return $query->where('e.head', $request->head);
            })->where('e.branch', $request->branch)->orderBy('e.created_at')->get();
        endif;
        return view('reports.income-expense', compact('branches', 'records', 'inputs', 'heads'));
    }

    public function showpayment(){
        if($this->isAdmin()):
            $branches = DB::table('branches')->get();
        else:
            $branches = $this->getBranches($this->branch);
        endif;
        $records = []; $inputs = []; 
        return view('reports.patient-payments', compact('branches', 'records', 'inputs'));
    }
    public function fetchpayment(Request $request){
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        if($this->isAdmin()):
            $branches = DB::table('branches')->get();
        else:
            $branches = $this->getBranches($this->branch);
        endif;
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        $records = DB::table('patient_payments as pp')->leftJoin('patient_registrations as pr', 'pp.patient_id', '=', 'pr.id')->leftJoin('branches as b', 'b.id', 'pp.branch')->leftJoin('users as u', 'u.id', '=', 'pp.created_by')->selectRaw("pr.patient_name, pr.patient_id, pp.medical_record_id, b.branch_name, u.name as uname, DATE_FORMAT(pp.created_at, '%d/%b/%Y %h:%i %p') AS pdate, pp.amount")->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $request->branch)->orderByDesc('pp.created_at')->get();
        return view('reports.patient-payments', compact('branches', 'records', 'inputs'));
    }
}
