<?php

namespace App\Livewire\Forms\Auth;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\Passwords\ResetPasswordDTO;
use App\Livewire\Concerns\Form\InteractWithDto;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResetPasswordForm extends Form
{
    use InteractWithDto;

    #[MapTo(ResetPasswordDTO::class)]
    #[Validate(['required'])]
    public string $token = '';

    #[MapTo(ResetPasswordDTO::class)]
    #[Url('email')]
    #[Validate(['required', 'email'])]
    public string $email = '';

    #[MapTo(ResetPasswordDTO::class)]
    #[Validate(['required', 'confirmed'])]
    public string $password = '';

    #[MapTo(ResetPasswordDTO::class, field: 'password_confirmation')]
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
