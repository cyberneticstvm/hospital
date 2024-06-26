<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Spectacle;
use App\Models\Branch;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    private $branch;

    function __construct()
    {

        $this->middleware('permission:spectacle-search|income-expense-search|kta-search', ['only' => ['spectaclefetch', 'iefetch', 'fetchPatient', 'fetchKta']]);
        $this->middleware('permission:spectacle-search', ['only' => ['spectaclefetch']]);
        $this->middleware('permission:income-expense-search', ['only' => ['iefetch']]);
        $this->middleware('permission:patient-search-datewise', ['only' => ['fetchPatient']]);
        $this->middleware('permission:kta-search', ['only' => ['fetchKta']]);
        $this->branch = session()->get('branch');
    }

    public function spectaclesearch()
    {
        $spectacles = [];
        $search_term = '';
        return view('search.spectacle', compact('spectacles', 'search_term'));
    }

    public function spectaclefetch(Request $request)
    {
        $this->validate($request, [
            'search_term' => 'required',
        ]);
        $input = $request->all();
        $search_term = $request->search_term;
        $spectacles = Spectacle::leftJoin('patient_medical_records AS m', 'spectacles.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'm.patient_id', '=', 'p.id')->leftJoin('users AS u', 'spectacles.created_by', '=', 'u.id')->selectRaw("spectacles.id, spectacles.medical_record_id, p.id as pid, p.patient_name, p.patient_id, u.name AS optometrist, DATE_FORMAT(spectacles.created_at, '%d/%b/%Y') AS pdate")->where('m.id', $search_term)->orWhere('p.patient_name', 'LIKE', "%{$search_term}%")->orWhere('p.mobile_number', 'LIKE', "%{$search_term}%")->orWhere('p.patient_id', 'LIKE', "%{$search_term}%")->get();
        return view('search.spectacle', compact('spectacles', 'search_term'));
    }

    public function iesearch()
    {
        $records = [];
        $inputs = [];
        $heads = DB::table('income_expense_heads')->get();
        return view('search.income-expense', compact('records', 'inputs', 'heads'));
    }

    public function iefetch(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'type' => 'required',
        ]);
        $input = $request->all();
        $heads = DB::table('income_expense_heads')->get();
        $inputs = array($request->from_date, $request->to_date, $request->type, $request->head);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->from_date)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/M/Y', $request->to_date)->format('Y-m-d');
        if ($request->type == 'I') :
            $records = DB::table('incomes')->leftJoin('branches as b', 'incomes.branch', '=', 'b.id')->leftJoin('income_expense_heads as h', 'h.id', '=', 'incomes.head')->select('incomes.id', 'b.branch_name', 'incomes.description', 'incomes.amount', DB::raw("DATE_FORMAT(incomes.date, '%d/%b/%Y') AS date"), 'h.name as hname')->whereBetween('date', [$startDate, $endDate])->where('branch', $this->branch)->when($request->head > 0, function ($query) use ($request) {
                return $query->where('incomes.head', $request->head);
            })->orderBy('incomes.date')->get();
        else :
            $records = DB::table('expenses')->leftJoin('branches as b', 'expenses.branch', '=', 'b.id')->leftJoin('income_expense_heads as h', 'h.id', '=', 'expenses.head')->select('expenses.id', 'b.branch_name', 'expenses.description', 'expenses.amount', DB::raw("DATE_FORMAT(expenses.date, '%d/%b/%Y') AS date"), 'h.name as hname')->whereBetween('date', [$startDate, $endDate])->where('branch', $this->branch)->when($request->head > 0, function ($query) use ($request) {
                return $query->where('expenses.head', $request->head);
            })->orderBy('expenses.date')->get();
        endif;
        return view('search.income-expense', compact('records', 'inputs', 'heads'));
    }

    public function searchPatient()
    {
        $patients = [];
        $inputs = [];
        if (in_array(Auth::user()->roles->first()->name, ['Admin'])) :
            $branches = Branch::all();
        else :
            $branches = Branch::where('id', $this->branch)->get();
        endif;
        return view('search.patient', compact('patients', 'branches', 'inputs'));
    }
    public function fetchPatient(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $branches = Branch::all();
        $inputs = array($request->from_date, $request->to_date, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->from_date)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->to_date)->endOfDay();
        $patients = DB::table('patient_registrations as p')->leftJoin('branches as b', 'p.branch', '=', 'b.id')->select('p.patient_name', 'p.age', 'p.patient_id', 'p.address', 'p.mobile_number', 'b.branch_name', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('p.created_at', [$startDate, $endDate])->when($request->branch > 0, function ($query) use ($request) {
            return $query->where('p.branch', $request->branch);
        })->orderByDesc('p.created_at')->get();
        return view('search.patient', compact('patients', 'branches', 'inputs'));
    }

    public function searchKta()
    {
        $kta = [];
        $inputs = [];
        $branches = Branch::all();
        return view('search.kta', compact('kta', 'branches', 'inputs'));
    }
    public function fetchKta(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'type' => 'required',
        ]);
        $branches = Branch::all();
        $inputs = array($request->from_date, $request->to_date, $request->type, $request->branch);
        $startDate = Carbon::createFromFormat('d/M/Y', $request->from_date)->startOfDay();
        $endDate = Carbon::createFromFormat('d/M/Y', $request->to_date)->endOfDay();
        if ($request->type == 'k') :
            $kta = DB::table('keratometries as k')->leftJoin('patient_registrations as p', 'p.id', '=', 'k.patient_id')->leftJoin('branches as b', 'b.id', '=', 'k.branch')->select('k.id', 'k.medical_record_id', 'p.patient_name', 'p.patient_id', 'b.branch_name', DB::raw("DATE_FORMAT(k.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('k.created_at', [$startDate, $endDate])->when($request->branch > 0, function ($query) use ($request) {
                return $query->where('k.branch', $request->branch);
            })->orderByDesc('k.created_at')->get();
        elseif ($request->type == 't') :
            $kta = DB::table('tonometries as t')->leftJoin('patient_registrations as p', 'p.id', '=', 't.patient_id')->leftJoin('branches as b', 'b.id', '=', 't.branch')->select('t.id', 't.medical_record_id', 'p.patient_name', 'p.patient_id', 'b.branch_name', DB::raw("DATE_FORMAT(t.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('t.created_at', [$startDate, $endDate])->when($request->branch > 0, function ($query) use ($request) {
                return $query->where('t.branch', $request->branch);
            })->orderByDesc('t.created_at')->get();
        else :
            $kta = DB::table('ascans as a')->leftJoin('patient_registrations as p', 'p.id', '=', 'a.patient_id')->leftJoin('branches as b', 'b.id', '=', 'a.branch')->select('a.id', 'a.medical_record_id', 'p.patient_name', 'p.patient_id', 'b.branch_name', DB::raw("DATE_FORMAT(a.created_at, '%d/%b/%Y') AS rdate"))->whereBetween('a.created_at', [$startDate, $endDate])->when($request->branch > 0, function ($query) use ($request) {
                return $query->where('a.branch', $request->branch);
            })->orderByDesc('a.created_at')->get();
        endif;
        return view('search.kta', compact('kta', 'branches', 'inputs'));
    }
}
