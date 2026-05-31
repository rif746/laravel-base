<?php

namespace App\Domains\Account\Actions;

class ResendVerificationEmail
{
    public function execute(): void
    {
        auth('web')->user()->sendEmailVerificationNotification();
    }
}
