<?php

namespace App\Domains\Auth\Actions;

class ResendVerificationEmail
{
    public function execute(): void
    {
        request()->user()->sendEmailVerificationNotification();
    }
}
