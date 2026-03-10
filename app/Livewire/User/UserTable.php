<?php

namespace App\Livewire\User;

use App\Livewire\Attributes\Metadata;
use App\Livewire\Module\BaseTable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Metadata(
    title: 'locale/user.title.table',
    description: 'locale/user.detail'
)]
class UserTable extends BaseTable
{
    use Toast;
    use WithPagination;

    #[Locked]
    public $title = 'locale/user.title.table';

    #[Url('q', history: true)]
    public $search = '';

    protected ?string $deletePermissionModel = User::class;

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
                'sortable' => true,
            ],
            [
                'key' => 'email',
                'label' => __('locale/user.field.email'),
                'sortable' => false,
            ],
            [
                'key' => 'role_name',
                'label' => __('locale/user.field.role'),
                'sortable' => false,
            ],
            [
                'key' => 'status',
                'label' => 'Status',
                'sortable' => false,
            ],
        ];
    }

    public function toggleStatus($id)
    {
        $user = User::where('id', $id)->update([
            'status' => DB::raw('NOT status'),
        ]);
        $user = User::where('id', $id)->get('status')->first();

        $this->success(
            title: trans_choice('locale/user.alert.status_toggled', $user->status),
        );
        $this->dispatch('user-table:reload');
    }

    public function delete($id)
    {
        parent::delete($id);
        User::destroy($id);
        $this->success(__('locale/user.alert.deleted'));
    }
}
