<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    #[Locked]
    public $id = null;

    #[Validate('required', as: 'Name')]
    public $name = null;

    public $permissions = [];

    public function load(int $id)
    {
        $role = Role::find($id);
        $this->id = $role->id;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('id')->toArray();
    }

    public function post()
    {
        $this->validate();

        return Role::updateOrCreate(['id' => $this->id], $this->all())
            ->permissions()
            ->sync($this->permissions);
    }
}
