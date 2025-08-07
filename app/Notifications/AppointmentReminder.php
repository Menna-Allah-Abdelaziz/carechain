<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Appointment Reminder')
                    ->line('You have an appointment today at ' . $this->appointmentTime)
                    ->action('View Appointment', url('/family/dashboard'));
    }

    protected $appointmentTime;

    public function __construct($appointmentTime)
    {
        $this->appointmentTime = $appointmentTime;
    }
}

