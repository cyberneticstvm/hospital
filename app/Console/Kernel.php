<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
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
            $branches = DB::table('branches')->all();
            foreach($branches as $key => $branch):
                $closing_balance = $this->getClosingBalance($branch->id);
                DB::table('branches')->where('id', $branch->id)->update(['closing_balance' => $closing_balance]);
            endforeach;
        })->everyFiveMinutes()->emailOutputOnFailure('cybernetics.me@outlook.com');
    }

    protected function getClosingBalance($branch){
        return 1110;
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
