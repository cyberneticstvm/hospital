<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\DoctorOptometrist;
use App\Models\User;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Session;
use DB;

class DashboardController extends Controller
{
    protected $branch;
    function __construct()
    {
        $this->branch = session()->get('branch');
    }

    public function test()
    {
        /*try{
            $patients = DB::table('patient_references as pr')->leftjoin('patient_registrations as p', 'p.id', '=', 'pr.patient_id')->leftJoin('doctors as d', 'd.id', '=', 'pr.doctor_id')->selectRaw("p.patient_name, p.mobile_number, pr.branch, d.doctor_name")->whereDate('pr.created_at', Carbon::today())->where('pr.status', 1)->get();
            if($patients->isNotEmpty()):
                foreach($patients as $key => $patient):
                    $branch = DB::table('branches')->find($patient->branch);
                    Config::set('myconfig.sms1.number', $patient->mobile_number);
                    Config::set('myconfig.sms1.message', "Dear ".$patient->patient_name." We are warm-heartedly thankful for consulting with ".$patient->doctor_name.". We would love your feedback, Post a review to our profile. ".$branch->review_link." Devi Eye Hospital. ".$branch->branch_name);
                    if($patient->mobile_number == '9188848860'):
                        $code = Helper::sendSms(Config::get('myconfig.sms1'));
                        //echo "sms sent to: ".$patient->mobile_number." with return ".$code."<br>";
                        print_r($code);
                    endif;
                endforeach;                
            endif;
        }catch(Exception $e){
            throw $e;
        }*/

        echo request()->ip();
    }

    public function userlogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        $ip = ($request->ip() == '127.0.0.1') ? '59.89.235.2' : $request->ip();
        $data = file_get_contents("https://ipinfo.io/$ip?token=38fa67afac8600");
        $obj = json_decode($data);
        $coordinates = explode(",", $obj->loc);
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $user_id = Auth::user()->id;
            if (Auth::user()->mobile_device == 0 && $this->is_mobile()) :
                Session::flush();
                Auth::logout();
                return Redirect('/')->withErrors("User not allowed to login to this device");
            endif;
            $device = ($this->is_mobile()) ? 'Mobile' : 'Computer';
            $branches = DB::table('branches')->leftJoin('user_branches', 'branches.id', '=', 'user_branches.branch_id')->select('branches.id', 'branches.branch_name')->where('user_branches.user_id', '=', $user_id)->get();
            $sid = Str::random(25);
            User::where('id', $user_id)->update(['session_id' => $sid]);
            LoginLog::insert(['user_id' => $user_id, 'session_id' => $sid, 'ip' => $request->ip(), 'city_name' => $obj->city, 'region_name' => $obj->region, 'country_name' => $obj->country, 'zip_code' => $obj->postal, 'device' => $device, 'latitude' => $coordinates[0], 'longitude' => $coordinates[1], 'logged_in' => Carbon::now()]);
            $branch_id = 0;
            $new_patients_count = 0;
            $review_count = 0;
            $cancelled = 0;
            $consultation = 0;
            $certificate = 0;
            $camp = 0;
            $vision = 0;
            $tot_patients = 0;
            $day_tot_income = 0;
            $day_tot_exp = 0;
            $income_monthly = 0.00;
            $expense_monthly = 0.00;
            $consultation_all_br = 0;
            return view('dash', compact('branches', 'branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'vision', 'tot_patients', 'day_tot_income', 'day_tot_exp', 'income_monthly', 'expense_monthly', 'consultation_all_br'));
            //return redirect()->route('dash')->with(['branches' => $branches]);
        }
        return redirect("/")->withErrors('Login details are not valid');
    }

    public function index()
    {
        $branch_id = $this->branch;
        $doc = DoctorOptometrist::where('optometrist_id', Auth::id())->whereDate('created_at', Carbon::today())->latest()->first();
        $tot_patients = DB::table('patient_registrations')->count('id');

        $new_patients_count = DB::table('patient_registrations')->where('branch', $branch_id)->whereDate('created_at', Carbon::today())->count('id');

        $review_count = DB::table('patient_references as r')->join('patient_registrations as p', 'p.id', '=', 'r.patient_id')->where('r.branch', $branch_id)->where('p.created_at', '<', Carbon::today())->whereDate('r.created_at', Carbon::today())->count('r.id');

        $cancelled = DB::table('patient_references as r')->where('status', 0)->where('r.branch', $branch_id)->whereDate('r.created_at', Carbon::today())->count('r.id');

        $consultation = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->when(Auth::user()->roles->first()->name == 'Doctor', function ($query) use ($branch_id) {
            $query->where('r.doctor_id', Auth::user()->doctor_id);
        })->when(Auth::user()->roles->first()->name == 'Optometriest', function ($query) use ($doc) {
            $query->where('r.doctor_id', $doc?->doctor_id ?? 0);
        })->whereDate('r.created_at', Carbon::today())->count('r.id');

        $consultation_all_br = DB::table('patient_references as r')->where('r.status', 1)->whereDate('r.created_at', Carbon::today())->count('r.id');

        $certificate = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->whereIn('r.consultation_type', [2, 3])->whereDate('r.created_at', Carbon::today())->count('r.id');

        $camp = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->whereIn('r.consultation_type', [4])->whereDate('r.created_at', Carbon::today())->count('r.id');

        $vision = DB::table('patient_references as r')->where('r.status', 1)->where('r.branch', $branch_id)->whereIn('r.consultation_type', [5])->whereDate('r.created_at', Carbon::today())->count('r.id');

        $day_tot_exp = DB::table('expenses')->where('branch', $branch_id)->where('head', '!=', 22)->whereDate('created_at', Carbon::today())->sum('amount');

        $day_tot_income = $this->getDayTotal();

        $income_monthly = DB::table('patient_payments')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('amount');

        $expense_monthly = DB::table('expenses')->where('head', '!=', 22)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('amount');

        if (Auth::user()->roles->first()->name == 'Admin') :
            return view('dash', compact('branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'vision', 'tot_patients', 'day_tot_income', 'day_tot_exp', 'income_monthly', 'expense_monthly', 'consultation_all_br'));
        elseif (Auth::user()->roles->first()->name == 'Accounts') :
            return view('dash-accounts', compact('branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'vision', 'tot_patients', 'day_tot_income', 'day_tot_exp', 'income_monthly', 'expense_monthly'));
        elseif (Auth::user()->roles->first()->name == 'Doctor') :
            return view('dash-doctor', compact('branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'vision', 'tot_patients', 'day_tot_income', 'day_tot_exp', 'income_monthly', 'expense_monthly'));
        else :
            return view('dash-other', compact('branch_id', 'new_patients_count', 'review_count', 'cancelled', 'consultation', 'certificate', 'camp', 'vision', 'tot_patients', 'day_tot_income', 'day_tot_exp', 'income_monthly', 'expense_monthly'));
        endif;
    }

    private function getDayTotal()
    {
        $reg_fee_total = DB::table('patient_registrations as pr')->leftJoin('patient_references as pref', 'pref.patient_id', 'pr.id')->where('pref.review', 'no')->whereDate('pref.created_at', Carbon::today())->where('pr.branch', $this->branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_references as pr')->whereDate('pr.created_at', Carbon::today())->where('pr.branch', $this->branch)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->where('pp.branch', $this->branch)->whereDate('pp.created_at', Carbon::today())->sum('pp.fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.branch_id', $this->branch)->whereDate('pc.created_at', Carbon::today())->where('pcd.status', 'I')->sum('pcd.fee');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $this->branch)->whereDate('p.created_at', Carbon::today())->sum('m.total');

        $pharmacy = DB::table('pharmacy_records as r')->leftJoin('pharmacies as p', 'p.id', '=', 'r.pharmacy_id')->whereDate('p.created_at', Carbon::today())->where('p.branch', $this->branch)->sum('r.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as p', 'p.id', '=', 's.medical_record_id')->whereDate('s.created_at', Carbon::today())->where('p.branch', $this->branch)->sum('s.fee');

        $tot = $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $medicine + $pharmacy + $vision;
        return $tot;
    }

    public function patientOverview()
    {
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

    public function patientMonth()
    {
        $patients = DB::table('patient_registrations')->selectRaw("COUNT(id) AS pcount, DATE_FORMAT(created_at, '%d/%b') AS day")->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->groupBy('day')->orderByDesc('id')->get();
        return json_encode($patients);
    }

    public function reviewMonth()
    {
        $patients = DB::table('patient_references')->selectRaw("COUNT(id) AS pcount, DATE_FORMAT(created_at, '%d/%b') AS day")->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->where('review', 'yes')->where('status', 1)->groupBy('day')->orderByDesc('id')->get();
        return json_encode($patients);
    }

    public function incomeExpense()
    {
        //$income = DB::select("select u.*, (select u.closing_balance - u2.closing_balance from daily_closing u2 where u2.branch = u.branch and u2.id < u.id order by u2.id desc limit 1 ) as diff from daily_closing u WHERE u.date BETWEEN LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY AND LAST_DAY(NOW()) AND u.branch = $branch")->get();
        //$records = DB::select("SELECT tbl1.day, tbl1.income, tbl1.cdate, SUM(e.amount) AS expense FROM (SELECT DATE(i.created_at) AS cdate, DATE_FORMAT(i.created_at, '%d/%b') AS day, SUM(i.amount) AS income FROM patient_payments i WHERE MONTH(i.created_at) = MONTH(NOW()) AND YEAR(i.created_at) = YEAR(NOW()) GROUP BY DATE(i.created_at) ORDER BY i.id DESC) AS tbl1 JOIN expenses e ON DATE(e.created_at) = tbl1.cdate GROUP BY tbl1.cdate ORDER BY tbl1.cdate DESC");
        $records = DB::select("SELECT tbl1.*, IFNULL(SUM(e.amount), 0) AS expense FROM (SELECT DATE(i.created_at) AS cdate, DATE_FORMAT(i.created_at, '%d/%b') AS day, SUM(i.amount) AS income FROM patient_payments i WHERE MONTH(i.created_at) = MONTH(NOW()) AND YEAR(i.created_at) = YEAR(NOW()) GROUP BY DATE(i.created_at) ORDER BY i.id DESC) AS tbl1 LEFT JOIN expenses e ON tbl1.cdate = e.date GROUP BY tbl1.cdate ORDER BY tbl1.cdate DESC");
        return json_encode($records);
    }

    public function is_mobile()
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $is_mobile = false;
        } else if (
            strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
            // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
        ) {
            $is_mobile = true;
        } else {
            $is_mobile = false;
        }
        return $is_mobile;
    }
}
