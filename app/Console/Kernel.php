<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AutoGetAttendance::class,
        Commands\AutoGetAttendanceHik::class,
        Commands\LeaveApproval::class,
        Commands\OfficialBusinessApproval::class,
        Commands\OvertimeApproval::class,
        Commands\WorkFromHomeApproval::class,
        Commands\DailyTimeRecordApproval::class,
        Commands\AutoEarnedLeave::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('command:auto_get_attendance')->twiceDaily(8,20);
        // $schedule->command('command:auto_get_attendance_hik')->twiceDaily(8,20);

        $schedule->command('command:leave_approval')->everyMinute();
        $schedule->command('command:official_business_approval')->everyMinute();
        $schedule->command('command:overtime_approval')->everyMinute();
        $schedule->command('command:work_from_home_approval')->everyMinute();
        $schedule->command('command:dtr_approval')->everyMinute();
        // $schedule->command('command:auto_earned_leave')->dailyAt('8:00');
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
