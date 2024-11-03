<?php

namespace App\Livewire\Module;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BaseTable extends Component
{
    use WithPagination;

    public $title = "Role Data";

    #[Url("so")]
    public $sortBy = ['column' => 'created_at', 'direction' => 'asc'];

    #[Url("q")]
    public $search = "";

    #[Url("per")]
    public $perPage = 5;

    protected null|string $deletePermissionModel = null;
    protected bool|string $deletePermission = false;

    public function mount()
    {
        seo(
            title: $this->title
        );
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    #[Computed]
    public function tableName()
    {
        $exploded_name = explode(".", $this->getName());
        return $exploded_name[count($exploded_name) - 1];
    }

    public function delete($id)
    {
        if (is_string($this->deletePermission)) {
            if ($this->deletePermissionModel != null) {
                $model = $this->deletePermissionModel::find($id);
            }
            $this->authorize($this->deletePermission, $model);
        } else {
            return abort_if(!$this->deletePermission, 403, 'This Action is Unauthorized');
        }
    }

    protected function getListeners()
    {
        $listen["{$this->tableName()}:reload"] = '$refresh';

        return array_merge($this->listeners, $listen);
    }
}
