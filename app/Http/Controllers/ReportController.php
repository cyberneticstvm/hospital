<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\IncomeExpenseHead as Head;

use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:report-daybook-show|report-daybook-fetch|report-income-expense-show|report-income-expense-fetch', ['only' => ['showdaybook','fetchdaybook','showincomeexpense','fetchincomeexpense']]);
        $this->middleware('permission:report-daybook-show', ['only' => ['showdaybook']]);
        $this->middleware('permission:report-daybook-fetch', ['only' => ['fetchdaybook']]);
        $this->middleware('permission:report-income-expense-show', ['only' => ['showincomeexpense']]);
        $this->middleware('permission:report-income-expense-fetch', ['only' => ['fetchincomeexpense']]);
    }

    public function showdaybook(){
        $branches = Branch::all();
        $records = []; $inputs = []; $income = 0.00; $expense = 0.00; $pharmacy = 0.00; $medicine = 0.00; $procs = 0.00;
        return view('reports.daybook', compact('branches', 'records', 'inputs', 'income', 'expense', 'medicine', 'pharmacy'));
    }
    public function fetchdaybook(Request $request){
        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
            'branch' => 'required',
        ]);
        $branches = Branch::all();
        $inputs = array($request->fromdate, $request->todate, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        $records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pr.id', '=', 'pref.patient_id')->leftJoin('patient_procedures as pp', function($q){
            $q->on('pmr.id', '=', 'pp.medical_record_id');
        })->select('pref.id', 'pr.patient_id', 'pr.patient_name', 'pref.doctor_fee', 'pr.registration_fee', DB::raw("IFNULL(SUM(pp.fee), 0.00) as proc_fee"))->where('pr.branch', $request->branch)->whereBetween('pref.created_at', [$startDate, $endDate])->where('pref.status', 1)->groupBy('pref.id')->get();
        $income = DB::table('incomes')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $request->branch)->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $procs = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pmr.branch', $request->branch)->whereBetween('pp.created_at', [$startDate, $endDate])->sum('fee');
        $pharmacy = DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $request->branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');
        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $request->branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');
        return view('reports.daybook', compact('branches', 'records', 'inputs', 'income', 'expense', 'pharmacy', 'medicine', 'procs'));
    }
    public function showincomeexpense(){
        $branches = Branch::all();
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
        $branches = Branch::all();
        $heads = Head::all();
        $inputs = array($request->fromdate, $request->todate, $request->branch, $request->type, $request->head);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->fromdate)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();
        if($request->type == 'I'):
            $records = DB::table('incomes as i')->leftJoin('income_expense_heads as h', 'i.head', '=', 'h.id')->leftJoin('branches as b', 'i.branch', '=', 'b.id')->select('i.id', DB::raw("DATE_FORMAT(i.created_at, '%d/%b/%Y') AS cdate"), 'i.description', 'i.amount', DB::raw("'Income' AS type"), 'b.branch_name', 'h.name')->whereBetween('i.created_at', [$startDate, $endDate])->when($request->head > 0, function($query) use ($request){
                return $query->where('i.head', $request->head);
            })->orderBy('i.created_at')->get();
        else:
            $records = DB::table('expenses as e')->leftJoin('income_expense_heads as h', 'e.head', '=', 'h.id')->leftJoin('branches as b', 'e.branch', '=', 'b.id')->select('e.id', DB::raw("DATE_FORMAT(e.created_at, '%d/%b/%Y') AS cdate"), 'e.description', 'e.amount', DB::raw("'Expense' AS type"), 'b.branch_name', 'h.name')->whereBetween('e.created_at', [$startDate, $endDate])->when($request->head > 0, function($query) use ($request){
                return $query->where('e.head', $request->head);
            })->orderBy('e.created_at')->get();
        endif;
        return view('reports.income-expense', compact('branches', 'records', 'inputs', 'heads'));
    }
}
