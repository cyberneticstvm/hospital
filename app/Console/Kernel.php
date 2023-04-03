<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Config;
use App\Helper\Helper;
use App\Models\PatientSurgeryConsumable;
use Carbon\Carbon;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $branches = DB::table('branches')->get();
            foreach($branches as $key => $branch):
                $closing_balance = $this->getClosingBalance($branch->id);
                //DB::table('branches')->where('id', $branch->id)->update(['closing_balance' => $closing_balance]);
                DB::table('daily_closing')->insert(
                    ['date' => Carbon::today(), 'closing_balance' => $closing_balance, 'branch' => $branch->id, 'closed_by' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
                );
            endforeach;
        })->dailyAt('23:30');

        $schedule->call(function(){
            $aps = DB::table('appointments')->selectRaw("patient_name, mobile_number, DATE_FORMAT(appointment_date, '%d/%b/%Y') AS adate, TIME_FORMAT(appointment_time, '%h:%i %p') AS atime")->whereDate('appointment_date', Carbon::today())->get();
            if($aps->isNotEmpty()):
                foreach($aps as $key => $app):
                    Config::set('myconfig.sms.number', $app->mobile_number);
                    Config::set('myconfig.sms.message', "Dear ".$app->patient_name.", Your appointment has been scheduled on ".$app->adate." ".$app->atime.", for enquiry please Call 9995050149. Thank You, Devi Eye Hospital.");
                    Helper::sendSms(Config::get('myconfig.sms'));
                endforeach;
            endif;
        })->dailyAt('08:30');

        $schedule->call(function(){
            $patients = DB::table('patient_references as pr')->leftjoin('patient_registrations as p', 'p.id', '=', 'pr.patient_id')->leftJoin('doctors as d', 'd.id', '=', 'pr.doctor_id')->selectRaw("p.patient_name, p.mobile_number, pr.branch, d.doctor_name")->whereDate('pr.created_at', Carbon::today())->where('pr.status', 1)->where('pr.sms', 1)->get();
            if($patients->isNotEmpty()):
                foreach($patients as $key => $patient):
                    $branch = DB::table('branches')->find($patient->branch);
                    Config::set('myconfig.sms1.number', $patient->mobile_number);
                    Config::set('myconfig.sms1.message', "Dear ".$patient->patient_name.", We are warm-heartedly thankful for consulting with ".$patient->doctor_name.". We would love your feedback, Post a review to our profile. ".$branch->review_link." Devi Eye Hospital. ".$branch->branch_name.".");
                    Helper::sendSms(Config::get('myconfig.sms1'));
                endforeach;
            endif;
        })->dailyAt('19:30');
    }

    private function getClosingBalance($branch){

        $prev_day = Carbon::today()->subDays(1);
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $opening_balance = DB::table('daily_closing as d')->select(DB::raw("MAX(d.id), IFNULL(d.closing_balance, 0) AS closing_balance"))->whereDate('d.date', '=', $prev_day)->where('d.branch', $branch)->orderByDesc('d.id')->first()->closing_balance;

        $reg_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->whereBetween('pmr.created_at', [$startDate, $endDate])->where('pr.branch', $branch)->sum('pr.registration_fee');

        $consultation_fee_total = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->whereBetween('pr.created_at', [$startDate, $endDate])->where('pr.branch', $branch)->where('pr.status', 1)->sum('pr.doctor_fee');

        $procedure_fee_total = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->whereBetween('pp.created_at', [$startDate, $endDate])->where('pp.branch', $branch)->sum('fee');

        $certificate_fee_total = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->whereBetween('pc.created_at', [$startDate, $endDate])->where('pc.branch_id', $branch)->where('pcd.status', 'I')->sum('pcd.fee');        

        $pharmacy = DB::table('pharmacies as p')->leftJoin('pharmacy_records as pr', 'p.id', '=', 'pr.pharmacy_id')->where('p.branch', $branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('pr.total');

        $medicine = DB::table('patient_medical_records as p')->leftJoin('patient_medicine_records as m', 'p.id', '=', 'm.medical_record_id')->where('m.status', 1)->where('p.branch', $branch)->whereBetween('p.created_at', [$startDate, $endDate])->sum('m.total');

        $vision = DB::table('spectacles as s')->leftJoin('patient_medical_records as m', 'm.id', '=', 's.medical_record_id')->where('m.branch', $branch)->whereBetween('s.created_at', [$startDate, $endDate])->sum('s.fee');

        $income = DB::table('incomes')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->sum('amount');

        $income_received_other = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->whereIn('payment_mode', [2,3,4,5,7])->sum('amount');

        $clinical_lab = DB::table('lab_clinics as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $branch)->sum('l.fee');

        $radiology_lab = DB::table('lab_radiologies as l')->leftJoin('patient_medical_records as m', 'm.id', '=', 'l.medical_record_id')->whereBetween('l.created_at', [$startDate, $endDate])->where('l.tested_from', 1)->where('m.branch', $branch)->sum('l.fee');

        $surgery_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'surgery')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $branch)->sum('d.total'); 

        $postop_medicine = DB::table('post_operative_medicine_details as d')->leftjoin('post_operative_medicines as m', 'm.id', 'd.pom_id')->where('m.type', 'postop')->whereBetween('d.created_at', [$startDate, $endDate])->where('m.branch', $branch)->sum('d.total');

        $surgery_consumables = DB::table('patient_surgery_consumable_lists as l')->leftJoin('patient_surgery_consumables as c', 'l.psc_id', '=', 'c.id')->whereBetween('c.created_at', [$startDate, $endDate])->where('c.branch', $branch)->sum('l.total');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $vision + $income + $clinical_lab + $radiology_lab + $surgery_medicine + $postop_medicine + $surgery_consumables;

        $closing_balance = $income_total-($income_received_other + $expense);

        return $closing_balance;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
