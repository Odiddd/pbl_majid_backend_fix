<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\UpdateKegiatanStatus::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(\App\Console\Commands\UpdateKegiatanStatus::class)->dailyAt('00:01');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
