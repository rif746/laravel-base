<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\Identity\Actions\Roles\CreateSystemRole;
use App\Domains\Identity\Actions\Roles\UpdateSystemRole;
use App\Domains\Identity\DTOs\Roles\CreateRoleDTO;
use App\Domains\Identity\DTOs\Roles\UpdateRoleDTO;
use App\Domains\Identity\Models\Permission;
use App\Domains\Identity\Models\Role;
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

    #[Locked]
    public string $mode = 'create';

    protected string $resourceName = 'role';

    #[Computed]
    public function permissions(): Collection
    {
        return Permission::all(['name', 'group', 'description']);
    }

    public function save(CreateSystemRole $create, UpdateSystemRole $update): void
    {
        $this->validate();

        if ($this->mode === 'create') {
            $create->execute(new CreateRoleDTO(
                name: $this->name,
                guard_name: $this->guard_name,
                permissions: $this->selected_permissions
            ));
        } elseif ($this->mode === 'update') {
            $update->execute(Role::findById($this->id), new UpdateRoleDTO(
                permissions: $this->selected_permissions,
            ));
        }

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
