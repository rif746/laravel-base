<?php

namespace App\Livewire\Module;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BaseTable extends Component
{
    use WithPagination;

    #[Url("so")]
    public $sort_by = "created_at";

    #[Url("sd")]
    public $sort_direction = "desc";

    #[Url("q")]
    public $search = "";

    #[Url("per")]
    public $perPage = 5;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sort_by == $field) {
            $this->sort_direction =
                $this->sort_direction == "desc" ? "asc" : "desc";
            return;
        }
        $this->sort_by = $field;
        $this->sort_direction = "desc";
    }
}
