<?php

namespace App\Livewire\Forms\Account;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdatePasswordForm extends Form
{
    #[Validate(as: 'domains/identity.fields.user.current_password')]
    public string $current_password = '';

    #[Validate(as: 'domains/identity.fields.user.new_password')]
    public string $new_password = '';

    #[Validate(as: 'domains/identity.fields.user.confirm_password')]
    public string $new_password_confirmation = '';

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }
}
