<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    protected $branch;
    function __construct(){
        $this->branch = session()->get('branch');
    }

    public function userlogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
   
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $user_id = Auth::user()->id;
            $branches = DB::table('branches')->leftJoin('user_branches', 'branches.id', '=', 'user_branches.branch_id')->select('branches.id', 'branches.branch_name')->where('user_branches.user_id', '=', $user_id)->get();
            $branch_id = 0; $new_patients_count = 0; $review_count = 0; $cancelled = 0; $consultation = 0; $certificate = 0; $camp = 0; $tot_patients = 0; $day_tot_income = 0; $day_tot_exp = 0;
            return view('dash', compact('branches', 'branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'tot_patients', 'day_tot_income', 'day_tot_exp'));
            //return redirect()->route('dash')->with(['branches' => $branches]);
        }  
        return redirect("/")->withErrors('Login details are not valid');
    }

    public function index(){
        $branch_id = $this->branch;

        $tot_patients = DB::table('patient_registrations')->count('id');

        $new_patients_count = DB::table('patient_registrations')->where('branch', $branch_id)->whereDate('created_at', Carbon::today())->count('id');

        $review_count = DB::table('patient_references as r')->join('patient_registrations as p', 'p.id', '=', 'r.patient_id')->where('r.branch', $branch_id)->where('p.created_at', '<', Carbon::today())->whereDate('r.created_at', Carbon::today())->count('r.id');

        $cancelled = DB::table('patient_references as r')->where('status', 0)->where('r.branch', $branch_id)->whereDate('r.created_at', Carbon::today())->count('r.id');

        $consultation = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->whereIn('r.consultation_type', [1,3])->whereDate('r.created_at', Carbon::today())->count('r.id');

        $certificate = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->whereIn('r.consultation_type', [2,3])->whereDate('r.created_at', Carbon::today())->count('r.id');

        $camp = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->whereIn('r.consultation_type', [4])->whereDate('r.created_at', Carbon::today())->count('r.id');

        $day_tot_exp = DB::table('expenses')->where('branch', $branch_id)->whereDate('created_at', Carbon::today())->sum('amount');

        $day_tot_income = $this->getDayTotal();

        return view('dash', compact('branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'tot_patients', 'day_tot_income', 'day_tot_exp'));
    }

    public function getDayTotal(){
        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->whereDate('pmr.created_at', Carbon::today())->where('pmr.branch', $this->branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.created_at', Carbon::today())->where('pmr.branch', $this->branch)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pp.branch', $this->branch)->where('pp.created_at', Carbon::today())->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.branch_id', $this->branch)->where('pc.created_at', Carbon::today())->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('p.created_at', Carbon::today())->where('p.branch', $this->branch)->where('m.status', 1)->sum('m.total');

        $pharmacy = DB::table('pharmacy_records as r')->leftJoin('pharmacies as p', 'p.id', '=', 'r.pharmacy_id')->whereDate('p.created_at', Carbon::today())->where('p.branch', $this->branch)->sum('r.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as p', 'p.id', '=', 's.medical_record_id')->whereDate('s.created_at', Carbon::today())->where('p.branch', $this->branch)->sum('s.fee');

        $tot = $reg_fee_total+$consultation_fee_total+$procedure_fee_total+$certificate_fee_total+$medicine+$pharmacy+$vision;
        return $reg_fee_total;
    }

    public function patientOverview(){
        $patients = DB::select("SELECT date, CONCAT_WS('-', SUBSTRING(MONTHNAME(date), 1, 3), DATE_FORMAT(date, '%y')) AS mname, COUNT(p.id) AS ptot FROM (
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 1 MONTH AS date UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 2 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 3 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 4 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 5 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 6 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 7 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 8 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 9 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 10 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 11 MONTH UNION ALL
		    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 12 MONTH
		) AS dates
		LEFT JOIN patient_registrations p ON p.created_at >= date AND p.created_at < date + INTERVAL 1 MONTH GROUP BY date");
        return json_encode($patients);
    }
}
