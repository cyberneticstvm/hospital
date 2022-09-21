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
        $records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('patient_references as pref', 'pr.id', '=', 'pref.patient_id')->leftJoin('patient_procedures as pp', 'pmr.id', '=', 'pp.medical_record_id')->select('pref.id', 'pr.patient_id', 'pr.patient_name', 'pref.doctor_fee', 'pr.registration_fee', DB::raw("IFNULL(SUM(pp.fee), 0.00) as proc_fee"))->where('pr.branch', $request->branch)->whereBetween('pref.created_at', [$startDate, $endDate])->where('pref.status', 1)->groupBy('pref.id')->get();
        return view('reports.daybook', compact('branches', 'records', 'inputs'));
    }
}
