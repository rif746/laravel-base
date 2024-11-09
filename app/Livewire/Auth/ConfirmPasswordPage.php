<?php

namespace App\Livewire\Auth;

use App\Livewire\Attributes\Metadata;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Metadata('Confirm Password')]
class ConfirmPasswordPage extends Component
{
    public $password;

    #[Layout("components.layouts.auth")]
    public function render()
    {
        return view('livewire.auth.confirm-password-page');
    }

    public function send()
    {
        if (! auth()->validate([
            'email' => request()->user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        request()->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended('/');
    }
}
