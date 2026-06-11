<?php

use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Actions\Onboarding\UpdateUser;
use App\Domains\Identity\DTOs\Onboarding\UpdateUserDTO;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use App\Livewire\Forms\Account\UpdateProfileForm;
use Illuminate\Contracts\Auth\Authenticatable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Locked]
    public ?int $id = null;

    #[Locked]
    public string $mode = 'update';

    public UpdateProfileForm $form;

    protected string $resourceName = 'profile';

    #[Computed]
    public function user(): Authenticatable|User|null
    {
        return auth('web')->user()->load(['profile']);
    }

    public function show(int|string $id): void
    {
        $profile = $this->user->profile;
        $this->form->fill($this->user->only(['name', 'email']));
        if ($profile) {
            $this->form->fill($profile->only(['gender', 'phone_number']));
            $this->form->date_of_birth = $profile->date_of_birth?->format('Y-m-d');
        }
        $this->id = $this->user->id;
    }

    public function save(UpdateProfile $updateProfile, UpdateUser $updateUser): void
    {
        $this->form->validate($this->form->rules($this->id));

        $profile = $this->user?->profile ?: new Profile(['user_id' => $this->id]);
        $updateUser->execute($this->user, new UpdateUserDTO(
            name: $this->form->name,
            email: $this->form->email,
        ));

        $updateProfile->execute($profile, new UpdateProfileDTO(
            userId: $this->id,
            gender: $this->form->gender,
            date_of_birth: $this->form->date_of_birth,
            phone_number: $this->form->phone_number,
        ));

        $this->success($this->message);
        $this->dispatch('hide-update-profile-modal');
        $this->dispatch('profile-updated');
    }

    public function hide(): void
    {
        $this->form->reset();
        $this->reset('id');
    }
};
