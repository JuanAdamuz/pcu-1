<?php

namespace App\Console;

use App\Console\Commands\CheckNames;
use App\Console\Commands\GenerateWhitelist;
use App\Console\Commands\GradeExams;
use App\Console\Commands\ImportPlayers;
use App\Console\Commands\ProcessReviews;
use App\Console\Commands\PruneAudits;
use App\Console\Commands\PurgeEmails;
use App\Console\Commands\PurgeExams;
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
        ProcessReviews::class,
        ImportPlayers::class,
        GradeExams::class,
        PurgeEmails::class,
        PurgeExams::class,
        GenerateWhitelist::class,
        CheckNames::class,
        PruneAudits::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        if (config('pcu.enable_schedule')) {
            $schedule->command('exams:grade')
                ->everyFiveMinutes();
            $schedule->command('reviews:process')
                ->everyTenMinutes();
            $schedule->command('purge:emails')
                ->daily();
            $schedule->command('purge:exams')
                ->daily();
            $schedule->command('whitelist:generate')
                ->cron('*/15 * * * * *')
                ->withoutOverlapping();
            $schedule->command('names:check')
                ->cron('1 4-22/6 * * *');
            $schedule->command('prune:audits')
                ->daily();
        }
    }

    /**
     * Register the Closure based commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
