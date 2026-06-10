<?php

use App\Domains\Identity\Actions\Passwords\UpdatePassword;
use App\Domains\Identity\DTOs\Passwords\UpdatePasswordDTO;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use App\Livewire\Forms\Account\UpdatePasswordForm;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Locked]
    public string $mode = 'update';

    public UpdatePasswordForm $form;

    protected string $resourceName = 'password';

    public function save(UpdatePassword $action): void
    {
        $this->form->validate();

        $action->execute(auth('web')->user(), new UpdatePasswordDTO(
            new_password: $this->form->new_password,
        ));

        $this->dispatch('hide-update-password-modal');
        $this->success($this->message);
    }

    public function hide(): void
    {
        $this->form->reset();
        $this->form->resetValidation();
    }
};
