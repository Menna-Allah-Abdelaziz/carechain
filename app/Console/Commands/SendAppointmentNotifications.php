<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;
use Carbon\Carbon;

class SendAppointmentNotifications extends Command
{
    protected $signature = 'notify:appointments';

    protected $description = 'Send notifications for upcoming appointments';

   public function handle()
{
    $factory = (new Factory)
        ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));

    $messaging = $factory->createMessaging();
$now = Carbon::now();
$nowFormatted = $now->format('Y-m-d H:i');

// ابحث عن المواعيد ضمن الدقيقة الحالية +/- دقيقة أو دقيقتين (لضمان عدم الفقدان)
$appointments = Appointment::whereBetween('appointment_time', [
    $now->copy()->subMinute(),
    $now->copy()->addMinute()
])->get();


    foreach ($appointments as $appointment) {
        $users = \App\Models\User::where('family_code', $appointment->family_code)->get();

        foreach ($users as $user) {
            if (!$user->fcm_token) {
                continue;
            }

            $notification = Notification::create(
                'Upcoming Appointment',
                "You have an appointment with Dr. {$appointment->doctor_name} at " . $appointment->appointment_time->format('H:i')
            );

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->toToken($user->fcm_token);

            try {
                $messaging->send($message);
                $this->info("Notification sent to user {$user->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send notification to user {$user->id}: " . $e->getMessage());
            }
        }

        }
    }
}
