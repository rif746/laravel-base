<?php

namespace App\Livewire\Module;

use Illuminate\Support\Facades\Gate;
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
        $listen["{$this->tableName()}:reload"] = '$refresh';

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
            'headers' => $this->headers(),
            'permissions' => $permissions,
            'import' => $this->import,
            'export' => $this->export,
            'modals' => $this->modals,
            'urls' => $this->urls,
        ];
    }
}
