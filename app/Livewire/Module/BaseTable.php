<?php

namespace App\Livewire\Module;

use Livewire\Attributes\Computed;
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

    protected function getListeners()
    {
        $exploded_name = explode(".", $this->getName());
        $exploded_name = $exploded_name[count($exploded_name) - 1];
        $listen["{$exploded_name}:reload"] = '$refresh';

        return array_merge($this->listeners, $listen);
    }

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
