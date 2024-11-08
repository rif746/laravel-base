<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseTable;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class UserTable extends BaseTable
{
    use Toast;
    use WithPagination;

    #[Locked]
    public $title = 'locale/user.title.table';

    #[Url('q', history: true)]
    public $search = '';

    protected null|string $deletePermissionModel = User::class;
    protected bool|string $deletePermission = 'delete';

    public function render()
    {
        return view('livewire.user.user-table');
    }

    #[Computed]
    public function users()
    {
        return User::search($this->search)
            ->orderBy(...$this->sortBy)
            ->paginate($this->perPage)
            ->onEachSide(0);
    }

    #[Computed]
    public function headers()
    {
        return [
            [
                'key' => 'name',
                'label' => __('locale/user.field.name'),
                'sort' => true,
            ],
            [
                'key' => 'email',
                'label' => __('locale/user.field.email'),
                'sort' => false,
            ],
            [
                'key' => 'status',
                'label' => 'Status',
                'sort' => false,
            ],
        ];
    }

    public function delete($id)
    {
        parent::delete($id);
        User::destroy($id);
        $this->success(__('locale/user.alert.deleted'));
    }
}
