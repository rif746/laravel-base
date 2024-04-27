<?php

namespace App\Livewire\Module;

use Illuminate\Support\Facades\Gate;
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

    /**
     * 
     * insert modal name to use in view here
     *
     * @var array('action' => 'modal-component')
     */
    protected array $modals = [
        'create' => '',
        'edit' => '',
        'view' => '',
    ];

    /**
     * 
     * @var array('action' => 'permission')
     */
    protected array $permissions = [
        'create' => false,
        'view' => false,
        'edit' => false,
        'delete' => false,
    ];

    /**
     * @var array
     */
    protected array $export = [];

    /**
     * @var array
     */
    protected array $import = [];

    /**
     * 
     * insert route name and parameter to use in view here
     *
     * @var array('action' => array('route' => 'route-name', 'params' => 'parameter'))
     */
    protected array $urls = [
        'create' => [
            'route' => '',
            'params' => ''
        ],
        'edit' => [
            'route' => '',
            'params' => ''
        ],
        'views' => [
            'route' => '',
            'params' => ''
        ],
    ];

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

    public function delete($id)
    {
        if (isset($this->permissions['delete'])) {
            if (is_bool($this->permissions['delete'])) {
                Gate::allowIf($this->permissions['delete']);
            } elseif (is_string($this->permissions['delete'])) {
                Gate::allows($this->permissions['delete']);
            } else {
                return false;
            }
        }
    }

    protected function getListeners()
    {
        $exploded_name = explode(".", $this->getName());
        $exploded_name = $exploded_name[count($exploded_name) - 1];
        $listen["{$exploded_name}:reload"] = '$refresh';

        return array_merge($this->listeners, $listen);
    }

    protected function getData()
    {
        // load permission using gate
        $permissions = [];
        foreach ($this->permissions as $key => $value) {
            if (is_bool($value)) {
                $permissions[$key] = $value;
            } elseif (is_string($value)) {
                $permissions[$key] = Gate::check($value);
            }
        }

        return [
            'cols' => $this->cols(),
            'permissions' => $permissions,
            'import' => $this->import,
            'export' => $this->export,
            'modals' => $this->modals,
            'urls' => $this->urls,
        ];
    }
}
