<?php

namespace App\Console;

use App\Console\Commands\MigrateDatabase;
use App\Console\Commands\RenameOrderFolder;
use App\Console\Commands\SendDelayedSms;
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
        SendDelayedSms::class,
        MigrateDatabase::class,
        RenameOrderFolder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sms:delayed:send')->everyThirtyMinutes()->between('8:00', '20:00');
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
