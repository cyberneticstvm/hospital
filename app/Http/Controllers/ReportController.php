<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:report-daybook-show|report-daybook-fetch', ['only' => ['showdaybook','fetchdaybook']]);
        $this->middleware('permission:report-daybook-show', ['only' => ['showdaybook']]);
        $this->middleware('permission:report-daybook-fetch', ['only' => ['fetchdaybook']]);
    }

    public function showdaybook(){
        $branches = Branch::all();
        $records = []; $inputs = [];
        return view('reports.daybook', compact('branches', 'records', 'inputs'));
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
        $endDate = Carbon::createFromFormat('d/M/Y', $request->todate)->endOfDay();;
        $records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('doctors as d', 'd.id', '=', 'pmr.doctor_id')->leftJoin('branches as b', 'pr.branch', '=', 'b.id')->leftJoin('patient_procedures as pp', 'pmr.id', '=', 'pp.medical_record_id')->select('pmr.id', 'pr.patient_id', 'pr.patient_name', 'd.doctor_fee', 'b.registration_fee', DB::raw("IFNULL(SUM(pp.fee), 0.00) as proc_fee"))->where('pr.branch', $request->branch)->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pmr.status', 1)->groupBy('pmr.id')->get();
        return view('reports.daybook', compact('branches', 'records', 'inputs'));
    }
}
