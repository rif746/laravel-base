<?php

use App\Domains\Identity\Actions\AccessControl\CreateSystemRole;
use App\Domains\Identity\Actions\AccessControl\UpdateSystemRole;
use App\Domains\Identity\DTOs\AccessControl\CreateRoleDTO;
use App\Domains\Identity\DTOs\AccessControl\UpdateRoleDTO;
use App\Domains\Identity\Models\Permission;
use App\Domains\Identity\Models\Role;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use App\Livewire\Forms\Identity\RoleForm;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use WithModal;
    use WithToast;

    #[Locked]
    public ?string $id = null;

    #[Locked]
    public string $mode = 'create';

    public RoleForm $form;

    protected string $resourceName = 'role';

    #[Computed]
    public function permissions(): Collection
    {
        return Permission::all(['name', 'group', 'description']);
    }

    public function save(CreateSystemRole $create, UpdateSystemRole $update): void
    {
        $this->form->validate();

        if ($this->mode === 'create') {
            $create->execute(new CreateRoleDTO(
                name: $this->form->name,
                guard_name: $this->form->guard_name,
                permissions: $this->form->selected_permissions
            ));
        } elseif ($this->mode === 'update') {
            $update->execute(Role::findById($this->id), new UpdateRoleDTO(
                permissions: $this->form->selected_permissions,
            ));
        }

        $this->success($this->message);
        $this->dispatch('hide-role-form-modal');
        $this->js("LaravelDataTables['role-table'].ajax.reload(null, false)");
        $this->form->reset();
        $this->form->resetValidation();
        $this->reset('id', 'mode');
    }

    public function show(int|string $id): void
    {
        $this->id = $id;
        $this->mode = 'update';
        $role = Role::with(['permissions' => fn ($q) => $q->select('name')])->findOrFail($id);
        $this->form->fill($role->only(['name', 'guard_name']));
        $this->form->selected_permissions = $role->permissions->pluck('name')->toArray();
    }

    public function hide(): void
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->reset('id', 'mode');
    }
};
