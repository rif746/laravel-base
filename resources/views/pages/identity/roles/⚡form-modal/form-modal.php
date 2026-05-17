<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Models\Identity\Permission;
use App\Models\Identity\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    use WithModal;

    #[Locked]
    public ?int $id = null;

    #[Validate('required')]
    public string $name = '';

    #[Validate('required')]
    public string $guard_name = '';

    #[Validate('required|array')]
    public array $selected_permissions = [];

    protected string $mode = 'create';

    protected string $resourceName = 'role';

    #[Computed]
    public function permissions()
    {
        return Permission::all(['name', 'group', 'description']);
    }

    public function save()
    {
        $this->validate();
        $role = Role::updateOrCreate(['id' => $this->id], [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);
        $role->syncPermissions($this->selected_permissions);
        $this->reset();
        $this->resetValidation();
        $this->dispatch('hide-role-form-modal');
        $this->js("LaravelDataTables['role-table'].ajax.reload()");
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
