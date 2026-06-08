<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Actions\Users\UpdateUser;
use App\Domains\Identity\DTOs\Users\UpdateUserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Locked]
    public ?int $id = null;

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

    #[Locked]
    public string $mode = 'update';

    protected string $resourceName = 'profile';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($this->id)],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($this->id)],
            'gender' => [Rule::in(GenderOption::cases())],
            'date_of_birth' => ['required'],
            'phone_number' => ['required', 'numeric', 'min_digits:10'],
        ];
    }

    #[Computed]
    public function user(): Authenticatable|User|null
    {
        return auth('web')->user()->load(['profile']);
    }

    public function show(int|string $id): void
    {
        $profile = $this->user->profile;
        $this->fill($this->user);
        if ($profile) {
            $this->fill($profile);
            $this->date_of_birth = $profile->date_of_birth?->format('Y-m-d');
        }
        $this->id = $this->user->id;
    }

    public function save(UpdateProfile $updateProfile, UpdateUser $updateUser): void
    {
        $this->validate();

        $profile = $this->user?->profile ?: new Profile(['user_id' => $this->id]);
        $updateUser->execute($this->user, new UpdateUserDTO(
            name: $this->name,
            email: $this->email,
        ));

        $updateProfile->execute($profile, new UpdateProfileDTO(
            userId: $this->id,
            gender: $this->gender,
            date_of_birth: $this->date_of_birth,
            phone_number: $this->phone_number,
        ));

        $this->success($this->message);
        $this->dispatch('hide-update-profile-modal');
        $this->dispatch('profile-updated');
    }

    public function hide(): void
    {
        $this->reset();
    }
};
