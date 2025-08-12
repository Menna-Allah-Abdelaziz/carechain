<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
       file_put_contents(storage_path('test.txt'), date('Y-m-d H:i:s') . " - Scheduler is working" . PHP_EOL, FILE_APPEND);
    })->everyMinute();

    $schedule->command('notify:appointments')->everyMinute();
    $schedule->command('notify:medications')->everyMinute(); // اضيفي السطر ده
}




protected function commands()
{
    $this->load(__DIR__.'/Commands');
    require base_path('routes/console.php');
}
}