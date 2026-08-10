<?php

namespace App\Livewire\Forms\Account;

use App\Attributes\Form\MapTo;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\Form\InteractWithDto;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateProfileForm extends Form
{
    use InteractWithDto;

    #[MapTo(UpdateUserIdentityDTO::class, context: 'update_user')]
    #[Validate(as: 'domains/identity/field.user.email')]
    public ?string $email = null;

    #[MapTo(UpdateUserIdentityDTO::class, context: 'update_user')]
    #[Validate(as: 'domains/identity/field.user.name')]
    public ?string $name = null;

    #[MapTo(UpdateProfileDTO::class, context: 'update_profile')]
    #[Validate(as: 'domains/account/field.profile.gender')]
    public ?GenderOption $gender = null;

    #[MapTo(UpdateProfileDTO::class, field: 'dateOfBirth', context: 'update_profile')]
    #[Validate(as: 'domains/account/field.profile.date_of_birth')]
    public ?string $date_of_birth = null;

    #[MapTo(UpdateProfileDTO::class, field: 'phoneNumber', context: 'update_profile')]
    #[Validate(as: 'domains/account/field.profile.phone_number')]
    public ?string $phone_number = null;

    public function rules(int $userId = 0): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($userId)],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($userId)],
            'gender' => ['required', new Enum(GenderOption::class)],
            'date_of_birth' => ['required'],
            'phone_number' => ['required', 'numeric', 'min_digits:10'],
        ];
    }
}
