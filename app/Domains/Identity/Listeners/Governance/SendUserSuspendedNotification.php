<?php

namespace App\Domains\Identity\Listeners\Governance;

use App\Domains\Identity\Events\Governance\UserWasSuspended;
use App\Domains\Identity\Mail\Governance\UserSuspendedEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserSuspendedNotification
{
    use InteractsWithQueue;

    public function handle(UserWasSuspended $event): void
    {
        Mail::to($event->email)->send(new UserSuspendedEmail);
    }
}
