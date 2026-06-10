<?php

namespace App\Livewire\Forms\Account;

use App\Domains\Account\Enums\GenderOption;
use App\Domains\Identity\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateProfileForm extends Form
{
    #[Validate(as: 'domains/identity.fields.user.email')]
    public ?string $email = null;

    #[Validate(as: 'domains/identity.fields.user.name')]
    public ?string $name = null;

    #[Validate(as: 'domains/account.fields.profile.gender')]
    public ?string $gender = null;

    #[Validate(as: 'domains/account.fields.profile.date_of_birth')]
    public ?string $date_of_birth = null;

    #[Validate(as: 'domains/account.fields.profile.phone_number')]
    public ?string $phone_number = null;

    public function rules(int $userId = 0): array
    {
        return [
            'name'         => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($userId)],
            'email'        => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($userId)],
            'gender'       => [Rule::in(GenderOption::cases())],
            'date_of_birth'=> ['required'],
            'phone_number' => ['required', 'numeric', 'min_digits:10'],
        ];
    }
}
