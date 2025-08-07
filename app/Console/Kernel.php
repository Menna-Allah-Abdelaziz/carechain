<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
       $schedule->call(function () {
       $appointments = \App\Models\Appointment::whereBetween('appointment_time', [
        now()->addHour()->startOfMinute(),
        now()->addHour()->endOfMinute(),
    ])->get();

    foreach ($appointments as $appointment) {
        $user = \App\Models\User::find($appointment->family_code);
        if ($user) {
            $user->notify(new \App\Notifications\AppointmentReminder($appointment->appointment_time));
        }
    }
   })->everyMinute();

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
