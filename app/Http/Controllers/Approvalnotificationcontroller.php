<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserApprovedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Approved!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We are pleased to inform you that your account has been approved by the admin.')
            ->action('Login Now', url('/login'))
            ->line('Thank you for registering with us!');
    }
}
