<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medication;
use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Carbon\Carbon;

class SendMedicationNotifications extends Command
{
    protected $signature = 'notify:medications';

    protected $description = 'Send notifications for medications dosing times';

    public function handle()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));

        $messaging = $factory->createMessaging();

        $now = Carbon::now();
        $nowFormatted = $now->format('H:i');

        // هنجلب الأدوية اللي وقت الجرعة الأولى بتاعتها قريبة من الوقت الحالي
        // نسمح بفارق دقيقة لكل جهة عشان ما نفقدش أي جرعة
        $medications = Medication::whereBetween('first_dose_time', [
            $now->copy()->subMinute()->format('H:i:s'),
            $now->copy()->addMinute()->format('H:i:s')
        ])->get();

        foreach ($medications as $medication) {
            // نجيب كل المستخدمين بنفس family_code الخاص بالدواء
            $users = User::where('family_code', $medication->family_code)->get();

            foreach ($users as $user) {
                if (!$user->fcm_token) {
                    continue;
                }

                $notification = Notification::create(
                    'Medication Reminder',
                    "It's time to take your medication: {$medication->name} - Dosage: {$medication->dosage}"
                );

                $message = CloudMessage::new()
                    ->withNotification($notification)
                    ->toToken($user->fcm_token);

                try {
                    $messaging->send($message);
                    $this->info("Notification sent to user {$user->id} for medication {$medication->name}");
                } catch (\Exception $e) {
                    $this->error("Failed to send notification to user {$user->id}: " . $e->getMessage());
                }
            }
        }
    }
}
