<?php

namespace App\Domains\Auth\Actions;

class ConfirmPassword
{
    public function execute(): void
    {
        session()->put('auth.password_confirmed_at', time());
    }
}
