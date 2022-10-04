<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        })->dailyAt('23:30')->emailOutputOnFailure('cybernetics.me@outlook.com');
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

        $income = DB::table('incomes')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense = DB::table('expenses')->where('branch', $branch)->whereBetween('date', [$startDate, $endDate])->sum('amount');

        $income_received_cash = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', 1)->sum('amount');

        $income_received_other = DB::table('patient_payments')->where('branch', $branch)->whereBetween('created_at', [$startDate, $endDate])->where('payment_mode', '!=', 1)->sum('amount');

        $income_total = $opening_balance + $reg_fee_total + $consultation_fee_total + $procedure_fee_total + $certificate_fee_total + $pharmacy + $medicine + $income;

        $closing_balance = $income_total-($income_received_cash + $income_received_other + $expense);

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
