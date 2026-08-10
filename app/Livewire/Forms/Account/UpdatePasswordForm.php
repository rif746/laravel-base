<?php

namespace App\Livewire\Forms\Account;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\Passwords\UpdatePasswordDTO;
use App\Livewire\Concerns\Form\InteractWithDto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdatePasswordForm extends Form
{
    use InteractWithDto;

    #[Validate(as: 'domains/identity/field.user.current_password')]
    public string $current_password = '';

    #[MapTo(UpdatePasswordDTO::class)]
    #[Validate(as: 'domains/identity/field.user.new_password')]
    public string $new_password = '';

    #[Validate(as: 'domains/identity/field.user.confirm_password')]
    public string $new_password_confirmation = '';

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }
}
