<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $deviceToken = $request->input('device_token');

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));

        $messaging = $factory->createMessaging();

        $notification = Notification::create('Notification Title', 'Notification body content');

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        try {
            $messaging->send($message);
            return response()->json(['success' => true, 'message' => 'Notification sent']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
