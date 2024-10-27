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
    public $title = 'User Data';

    #[Url('q', history: true)]
    public $search = '';

    protected array $permissions = [
        'create' => 'user create',
        'edit' => 'user edit',
        'delete' => 'user delete',
    ];

    public function render()
    {
        return view('livewire.user.user-table', $this->getData());
    }

    #[Computed]
    public function users()
    {
        return tap(User::search($this->search)
            ->orderBy(...$this->sortBy)
            ->paginate($this->perPage)
            ->onEachSide(0), fn ($query) => $query
            ->map(function ($user) {
                if ($user->name == auth()->user()->name) {
                    $user->no_delete = true;
                    $user->no_edit = true;
                }

                return $user;
            }));
    }

    public function headers()
    {
        return [
            [
                'key' => 'name',
                'label' => 'Name',
                'sort' => true,
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'sort' => false,
            ],
        ];
    }

    public function delete($id)
    {
        parent::delete($id);
        User::destroy($id);
        $this->success('Role deleted!');
    }
}
