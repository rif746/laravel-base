<?php

namespace App\Livewire\Role;

use App\Livewire\Module\BaseTable;
use App\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class RoleTable extends BaseTable
{
    use Toast;
    use WithPagination;

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
    public function roles()
    {
        return Role::search($this->search)
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage)
            ->onEachSide(2);
    }

    public function headers()
    {
        return [
            [
                "key" => "name",
                "label" => __('locale/role.field.name'),
                "sort" => true,
            ],
        ];
    }

    public function delete($id)
    {
        parent::delete($id);
        Role::destroy($id);
        $this->success('Role deleted!');
    }
}
