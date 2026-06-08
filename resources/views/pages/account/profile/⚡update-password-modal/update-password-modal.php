<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\Identity\Actions\Passwords\UpdatePassword;
use App\Domains\Identity\DTOs\Passwords\UpdatePasswordDTO;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Validate(as: 'domains/identity.fields.user.current_password')]
    public string $current_password;

    #[Validate(as: 'domains/identity.fields.user.new_password')]
    public string $new_password;

    #[Validate(as: 'domains/identity.fields.user.confirm_password')]
    public string $new_password_confirmation;

    #[Locked]
    public string $mode = 'update';

    protected string $resourceName = 'password';

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }

    public function save(UpdatePassword $action): void
    {
        $this->validate();

        $action->execute(auth('web')->user(), new UpdatePasswordDTO(
            new_password: $this->new_password,
        ));

        $this->dispatch('hide-update-password-modal');
        $this->success($this->message);
    }

    public function hide(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }
};
