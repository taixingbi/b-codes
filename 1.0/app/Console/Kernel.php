<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Log;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
//        \App\Console\Commands\Inspire::class,
        Commands\SendEmails::class,
        Commands\UpdateCalendar::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
//        $schedule->command('email:send')->name('SendEmails')->everyMinute()->withoutOverlapping();
        $schedule->command('calendar:update')->name('UpdateCalendar')->everyMinute()->withoutOverlapping();

         Log::info("cron");
//        $schedule->call(function () {
//            DB::table('test2')->insert(
//                ['name' => 'johnklkex']
//            );
//        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');

    }
}
