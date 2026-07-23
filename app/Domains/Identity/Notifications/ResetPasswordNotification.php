<?php

namespace App\Domains\Identity\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        return (new MailMessage)
            ->subject(__('domains/auth/notifications.reset_password.subject'))
            ->line(__('domains/auth/notifications.reset_password.intro'))
            ->action(__('domains/auth/notifications.reset_password.action'), $url)
            ->line(__('domains/auth/notifications.reset_password.outro'));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
