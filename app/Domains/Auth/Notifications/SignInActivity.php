<?php

namespace App\Domains\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SignInActivity extends Notification
{
    use Queueable;

    public string $ip;
    public string $userAgent;

    /**
     * Create a new notification instance.
     */
    public function __construct(?string $ip = null, ?string $userAgent = null)
    {
        $this->ip = $ip ?? request()->ip() ?? 'Unknown';
        $this->userAgent = $userAgent ?? request()->userAgent() ?? 'Unknown';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('domains/auth.notifications.sign_in_activity.subject'))
            ->greeting(__('domains/auth.notifications.sign_in_activity.greeting', ['name' => $notifiable->name ?? '']))
            ->line(__('domains/auth.notifications.sign_in_activity.intro', ['app' => config('app.name')]))
            ->line(__('domains/auth.notifications.sign_in_activity.time', ['time' => now()->toDayDateTimeString()]))
            ->line(__('domains/auth.notifications.sign_in_activity.ip', ['ip' => $this->ip]))
            ->line(__('domains/auth.notifications.sign_in_activity.browser', ['browser' => $this->userAgent]))
            ->line(__('domains/auth.notifications.sign_in_activity.outro'))
            ->action(__('domains/auth.notifications.sign_in_activity.action'), url('/dashboard'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('domains/auth.notifications.sign_in_activity.title'),
            'message' => __('domains/auth.notifications.sign_in_activity.message', ['ip' => $this->ip]),
            'url' => url('/dashboard'),
        ];
    }
}
