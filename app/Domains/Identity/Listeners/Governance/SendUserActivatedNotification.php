<?php

namespace App\Domains\Identity\Listeners\Governance;

use App\Domains\Identity\Events\Governance\UserWasActivated;
use App\Domains\Identity\Mail\Governance\UserActivatedEmail;
use Illuminate\Support\Facades\Mail;

class SendUserActivatedNotification
{
    public function handle(UserWasActivated $event): void
    {
        Mail::to($event->email)->send(new UserActivatedEmail);
    }
}
