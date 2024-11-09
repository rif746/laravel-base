<?php

namespace App\Livewire\Role;

use App\Livewire\Attributes\Metadata;
use App\Livewire\Module\BaseTable;
use App\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Metadata(
    title: 'locale/role.title.table',
    description: 'locale/role.detail'
)]
class RoleTable extends BaseTable
{
    use Toast;
    use WithPagination;

    #[Locked]
    public $title = "locale/role.title.table";

    protected null|string $deletePermissionModel = Role::class;
    protected bool|string $deletePermission = false;

    public function render()
    {
        return view("livewire.role.role-table");
    }

    #[Computed]
    public function roles()
    {
        return Role::search($this->search)
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage)
            ->onEachSide(2);
    }

    #[Computed]
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
