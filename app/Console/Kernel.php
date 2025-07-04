<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
   protected function schedule(Schedule $schedule)
{
    $schedule->command('posts:publish')->everyMinute();
}

protected function commands()
{
    $this->load(__DIR__.'/Commands');
    // ...
}
}
