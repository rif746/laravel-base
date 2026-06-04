<?php

namespace App\Domains\Identity\Listeners;

use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use App\Domains\Identity\Notifications\SignInActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class SendSignInActivityNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     *
     * @var array<int, int>
     */
    public array $backoff = [10, 30, 60];

    /**
     * Handle the event.
     *
     * Sends a sign-in activity notification (email + database) to the user.
     * Runs asynchronously via the queue — fully decoupled from the HTTP session.
     */
    public function handle(UserLoggedIn $event): void
    {
        $event->user->notify(
            new SignInActivity($event->ipAddress, $event->userAgent),
        );
    }

    /**
     * Handle a job failure.
     */
    public function failed(UserLoggedIn $event, Throwable $exception): void
    {
        report($exception);
    }
}
