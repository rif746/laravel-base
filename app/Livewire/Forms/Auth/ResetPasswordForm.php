<?php

namespace App\Livewire\Forms\Auth;

use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResetPasswordForm extends Form
{
    #[Validate(['required'])]
    public string $token = '';

    #[Url('email')]
    #[Validate(['required', 'email'])]
    public string $email = '';

    #[Validate(['required', 'confirmed'])]
    public string $password = '';

    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ];
    }
}
