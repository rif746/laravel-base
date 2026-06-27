<?php

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Locked]
    public ?string $id = null;

    #[Validate]
    public ?string $role = null;

    #[Locked]
    public string $mode = 'update';

    protected string $resourceName = 'role';

    public function rules(): array
    {
        return [
            'role' => ['required', 'string'],
        ];
    }

    public function mount(): void
    {
        $this->role = $this->user?->role_name;
    }

    #[Computed]
    public function user(): User
    {
        return $this->id ? User::find($this->id) : new User;
    }

    #[Computed]
    public function roles(): array
    {
        return Role::select(['name'])
            ->get()
            ->pluck('name', 'name')
            ->toArray();
    }

    public function save(UpdateUserRole $updateUserRole): void
    {
        $this->validate();
        $updateUserRole->execute($this->user, [$this->role]);
        $this->dispatch('hide-role-selection-modal');
        $this->dispatch('refresh-user-data');
    }

    public function hide(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->dispatch('role-clear');
    }
};
