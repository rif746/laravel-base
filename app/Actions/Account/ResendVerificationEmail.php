<?php

namespace App\Actions\Account;

class ResendVerificationEmail
{
    public function execute(): void
    {
        auth('web')->user()->sendEmailVerificationNotification();
    }
}
