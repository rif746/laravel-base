<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Enums\Identity\GenderOption;
use App\Models\Identity\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;

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

    protected string $mode = 'update';

    protected string $resourceName = 'profile';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->ignore($this->id)],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($this->id)],
            'gender' => [Rule::in(GenderOption::cases())],
            'date_of_birth' => ['required'],
            'phone_number' => ['required', 'numeric', 'min_digits:10'],
        ];
    }

    public function show(int|string $id): void
    {
        $user = auth('web')->user();
        $profile = $user->load(['profile'])->profile;
        $this->fill($user);
        if ($profile) {
            $this->fill($profile);
            $this->date_of_birth = $profile->date_of_birth?->format('Y-m-d');
        }
        $this->id = $user->id;
    }

    public function save()
    {
        $this->validate();
        $user = auth('web')->user();
        $user->fill($this->only('email', 'name'));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->update();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $this->only('gender', 'date_of_birth', 'phone_number')
        );

        $this->js("toast('".__('ui.crud.success.updated', ['resource' => __('resources.profile')])."')");
        $this->dispatch('hide-update-profile-modal');
        $this->dispatch('reload-user-info');
    }

    public function hide(): void
    {
        $this->reset();
    }
};
