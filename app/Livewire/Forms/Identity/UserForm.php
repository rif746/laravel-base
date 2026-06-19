<?php

namespace App\Livewire\Forms\Identity;

use App\Domains\Identity\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Validate(as: 'domains/identity/field.user.email')]
    public ?string $email = null;

    #[Validate(as: 'domains/identity/field.user.name')]
    public ?string $name = null;

    #[Validate(as: 'domains/identity/field.role.name')]
    public ?string $role_name = null;

    #[Validate(as: 'domains/identity/field.user.password')]
    public ?string $password = null;

    #[Validate(as: 'domains/identity/field.user.password_confirmation')]
    public ?string $password_confirmation = null;

    public function rules(int $userId = 0, bool $isUpdate = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($userId)],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($userId)],
            'role_name' => ['required'],
            'password' => [Password::default(), 'required', 'confirmed'],
        ];

        if ($isUpdate) {
            unset($rules['password']);
        }

        return $rules;
    }
}
