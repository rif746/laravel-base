<?php

namespace App\Domains\Identity\Listeners\Governance;

use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\Identity\Mail\Governance\UserPurgedEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserPurgedNotification
{
    use InteractsWithQueue;

    public function handle(UserWasPurged $event): void
    {
        Mail::to($event->email)->send(new UserPurgedEmail);
    }
}
