<?php

use App\Actions\Identity\SaveRole;
use App\Concerns\Livewire\Shared\WithModal;
use App\Concerns\Livewire\Shared\WithToast;
use App\DTOs\Identity\RoleDTO;
use App\Models\Identity\Permission;
use App\Models\Identity\Role;
use Illuminate\Support\Collection;
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

    #[Validate('required')]
    public string $name = '';

    #[Validate('required')]
    public string $guard_name = '';

    #[Validate('required|array')]
    public array $selected_permissions = [];

    public string $mode = 'create';

    protected string $resourceName = 'role';

    #[Computed]
    public function permissions(): Collection
    {
        return Permission::all(['name', 'group', 'description']);
    }

    public function save(SaveRole $action): void
    {
        $this->validate();

        $action->execute(new RoleDTO(
            id: $this->id,
            name: $this->name,
            guard_name: $this->guard_name,
            selected_permissions: $this->selected_permissions,
        ));

        $this->success($this->message);
        $this->dispatch('hide-role-form-modal');
        $this->js("LaravelDataTables['role-table'].ajax.reload()");
        $this->reset();
        $this->resetValidation();
    }

    public function show(int|string $id): void
    {
        $this->id = $id;
        $this->mode = 'update';
        $role = Role::with(['permissions' => fn ($q) => $q->select('name')])->findOrFail($id);
        $this->fill($role);
        $this->selected_permissions = $role->permissions->pluck('name')->toArray();
    }

    public function hide(): void
    {
        $this->reset();
        $this->resetValidation();
    }
};
