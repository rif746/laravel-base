<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseTable;
use App\Models\User;
use Livewire\Attributes\Computed;

class UserTable extends BaseTable
{
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
        ];
    }

    #[Computed]
    public function users()
    {
        return User::orderBy($this->sort_by, $this->sort_direction)->paginate(
            $this->perPage
        );
    }
}
