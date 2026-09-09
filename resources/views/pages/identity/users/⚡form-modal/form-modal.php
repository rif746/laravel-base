<?php

use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserIdentity;
use App\Domains\Identity\Actions\Onboarding\ProvisionNewUser;
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
    public ?string $ulid = null;

    #[Locked]
    public string $mode = 'create';

    public UserForm $form;

    protected string $resourceName = 'user';

    public function save(ProvisionNewUser $create, UpdateUserIdentity $update): void
    {
        $this->form->validate();

        if ($this->mode === 'create') {
            $create->execute($this->form->toDto('create'));
        } elseif ($this->mode === 'update') {
            $update->execute($this->user, $this->form->toDto('update'));
        }

        $this->success($this->message);
        $this->dispatch('hide-user-form-modal');
        $this->js("LaravelDataTables['user-table'].ajax.reload(null, false)");
    }

    #[Computed]
    public function user(): ?User
    {
        return $this->ulid ? User::where('ulid', $this->ulid)->first() : null;
    }

    public function show(int|string $id): void
    {
        $this->ulid = $id;
        $this->mode = 'update';
        $this->form->ulid = $id;
        $this->form->fill($this->user->only(['name', 'email']));
        info($this->getErrorBag());
    }

    public function hide(): void
    {
        $this->form->resetValidation();
        $this->reset('ulid', 'mode', 'form');
    }
};
