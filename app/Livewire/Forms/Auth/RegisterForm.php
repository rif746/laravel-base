<?php

namespace App\Livewire\Forms\Auth;

use App\Domains\Identity\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    #[Validate(as: 'name')]
    public string $name = '';

    #[Validate(as: 'email')]
    public string $email = '';

    #[Validate(as: 'password')]
    public string $password = '';

    #[Validate(as: 'password confirmation')]
    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')],
            'password' => [Password::default(), 'required', 'confirmed'],
        ];
    }
}
