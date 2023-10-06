<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseTable;
use App\Livewire\Module\Trait\Notification;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;

class UserTable extends BaseTable
{
    use Notification;

    #[Locked]
    public $title = "User Table";

    #[Url('q', history: true)]
    public $search = "";

    protected array $modals = [
        'edit' => 'user-form-modal',
        'view' => '',
    ];

    protected array $urls = [
        'edit' => [
            'route' => '',
            'params' => ''
        ],
        'views' => [
            'route' => '',
            'params' => ''
        ],
    ];

    protected array $permissions = [
        'create' => true,
        'edit' => true,
        'delete' => true,
    ];

    public function render()
    {
        return view("livewire.user.user-table", $this->getData());
    }

    #[Computed]
    public function users()
    {
        return User::search($this->search)
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
            [
                "label" => "Email",
                "query" => "email",
                "sort" => true,
            ],
        ];
    }

    public function delete($id)
    {
        parent::delete($id);
        User::destroy($id);
    }
}
