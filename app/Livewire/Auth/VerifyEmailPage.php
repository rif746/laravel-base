<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use App\Livewire\Attributes\Metadata;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

#[Metadata('Verify Email')]
class VerifyEmailPage extends Component
{
    use Toast;

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.auth.verify-email-page');
    }

    public function logout()
    {
        $logout = new(Logout::class);
        $logout();
    }

    public function resendEmail()
    {
        if (auth('web')->user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        auth('web')->user()->sendEmailVerificationNotification();
        $this->success('Email verification sent.');
    }
}
