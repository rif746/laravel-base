<?php

namespace App\Livewire\Forms\Identity;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\Form\InteractWithDto;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    use InteractWithDto;

    #[MapTo(ProvisionUserDTO::class, context: 'create')]
    #[MapTo(UpdateUserIdentityDTO::class, context: 'update')]
    #[Validate(as: 'domains/identity/field.user.email')]
    public ?string $email = null;

    #[MapTo(ProvisionUserDTO::class, context: 'create')]
    #[MapTo(UpdateUserIdentityDTO::class, context: 'update')]
    #[Validate(as: 'domains/identity/field.user.name')]
    public ?string $name = null;

    #[MapTo(ProvisionUserDTO::class, field: 'role', context: 'create')]
    #[Validate(as: 'domains/identity/field.role.name')]
    public ?string $role_name = null;

    #[MapTo(ProvisionUserDTO::class, context: 'create')]
    #[Validate(as: 'domains/identity/field.user.password')]
    public ?string $password = null;

    #[Validate(as: 'domains/identity/field.user.password_confirmation')]
    public ?string $password_confirmation = null;

    public function rules(string $userId = '', bool $isUpdate = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($userId, 'ulid')],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($userId, 'ulid')],
            'role_name' => ['required'],
            'password' => [Password::default(), 'required', 'confirmed'],
        ];

        if ($isUpdate) {
            unset($rules['password']);
            unset($rules['role_name']);
        }

        return $rules;
    }
}
