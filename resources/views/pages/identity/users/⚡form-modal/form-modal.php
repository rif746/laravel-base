<?php

use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserIdentity;
use App\Domains\Identity\Actions\Onboarding\ProvisionNewUser;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use App\Livewire\Forms\Identity\UserForm;
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
    public string $mode = 'create';

    public UserForm $form;

    protected string $resourceName = 'user';

    public function save(ProvisionNewUser $create, UpdateUserIdentity $update): void
    {
        $this->form->validate($this->form->rules($this->id ?? 0, $this->mode === 'update'));

        if ($this->mode === 'create') {
            $create->execute(new ProvisionUserDTO(
                name: $this->form->name,
                email: $this->form->email,
                password: $this->form->password,
                role: $this->form->role_name,
            ));
        } elseif ($this->mode === 'update') {
            $update->execute($this->user, new UpdateUserIdentityDTO(
                name: $this->form->name,
                email: $this->form->email,
            ));
        }

        $this->success($this->message);
        $this->dispatch('hide-user-form-modal');
        $this->js("LaravelDataTables['user-table'].ajax.reload()");
    }

    #[Computed]
    public function user(): ?User
    {
        return $this->id ? User::findOrFail($this->id) : null;
    }

    public function show(int|string $id): void
    {
        $this->id = $id;
        $this->mode = 'update';
        $this->form->fill($this->user->only(['name', 'email']));
        $this->form->role_name = $this->user->role_name;
    }

    public function hide(): void
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->reset('id', 'mode');
    }
};
