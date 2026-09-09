<?php

namespace App\Livewire\Forms\Identity;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\Form\InteractWithDto;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    use InteractWithDto;

    #[Locked]
    #[Validate]
    public string|int|null $ulid = null;

    #[MapTo(ProvisionUserDTO::class, context: 'create')]
    #[MapTo(UpdateUserIdentityDTO::class, context: 'update')]
    #[Validate(as: 'domains/identity/field.user.email', onUpdate: false)]
    public ?string $email = null;

    #[MapTo(ProvisionUserDTO::class, context: 'create')]
    #[MapTo(UpdateUserIdentityDTO::class, context: 'update')]
    #[Validate(as: 'domains/identity/field.user.name', onUpdate: false)]
    public ?string $name = null;

    #[MapTo(ProvisionUserDTO::class, field: 'role', context: 'create')]
    #[Validate(as: 'domains/identity/field.role.name', onUpdate: false)]
    public ?string $role_name = null;

    #[MapTo(ProvisionUserDTO::class, context: 'create')]
    #[Validate(as: 'domains/identity/field.user.password', onUpdate: false)]
    public ?string $password = null;

    #[Validate(as: 'domains/identity/field.user.password_confirmation', onUpdate: false)]
    public ?string $password_confirmation = null;

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($this->ulid, 'ulid')],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($this->ulid, 'ulid')],
        ];

        if (is_null($this->ulid)) {
            $rules['password'] = [Password::default(), 'required', 'confirmed'];
        }

        return $rules;
    }
}
