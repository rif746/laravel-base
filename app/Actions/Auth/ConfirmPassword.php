<?php

namespace App\Actions\Auth;

class ConfirmPassword
{
    public function execute(): void
    {
        session()->put('auth.password_confirmed_at', time());
    }
}
