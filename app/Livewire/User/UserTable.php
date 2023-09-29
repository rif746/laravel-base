<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseTable;
use App\Livewire\Module\Trait\Notification;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class UserTable extends BaseTable
{
    use Notification;

    #[Url('q', history: true)]
    public $search = "";

    public function render()
    {
        return view("livewire.user.user-table");
    }

    #[Computed]
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

    #[Computed]
    public function users()
    {
        return User::search($this->search)
            ->orderBy($this->sort_by, $this->sort_direction)
            ->paginate($this->perPage);
    }

    public function delete($id)
    {
    }
}
