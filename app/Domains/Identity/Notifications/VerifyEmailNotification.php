<?php

namespace App\Domains\Identity\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(__('domains/auth/notifications.verify_email.subject'))
            ->line(__('domains/auth/notifications.verify_email.intro'))
            ->action(__('domains/auth/notifications.verify_email.action'), $url)
            ->line(__('domains/auth/notifications.verify_email.outro'));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
