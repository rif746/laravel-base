<?php

namespace App\Actions\Auth;

class ResendVerificationEmail
{
    public function execute(): void
    {
        request()->user()->sendEmailVerificationNotification();
    }
}
