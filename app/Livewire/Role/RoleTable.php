<?php

namespace App\Livewire\Role;

use App\Livewire\Module\BaseTable;
use App\Livewire\Module\Trait\Notification;
use App\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

class RoleTable extends BaseTable
{
    use Notification;

    #[Locked]
    public $title = "Role Data";

    protected array $permissions = [
        'create' => 'role create',
        'edit' => 'role edit',
        'delete' => 'role delete',
    ];

    protected array $modals = [
        'create' => 'role-form-modal',
        'edit' => 'role-form-modal',
    ];

    public function render()
    {
        return view("livewire.role.role-table", $this->getData());
    }

    #[Computed]
    public function rows()
    {
        return Role::search($this->search)
            ->orderBy($this->sort_by, $this->sort_direction)
            ->paginate($this->perPage); 
    }

    public function cols()
    {
        return [
            [
                "label" => "Name",
                "query" => "name",
                "sort" => true,
            ],
        ];
    }

    public function delete($id)
    {
        parent::delete($id);
        Role::destroy($id);
        $this->toast('User deleted!');
    }
}
