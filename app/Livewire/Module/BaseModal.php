<?php

namespace App\Livewire\Module;

use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class BaseModal extends Component
{
    #[Locked]
    public $load_state = false;

    public $modal = false;

    /**
     * normal modal title
     * @var string
     */
    protected static $title;

    /**
     * load modal title
     * @var string
     */
    protected static $load_title;

    /**
     * save or load permission
     * @var array
     */
    protected $permission = [
        'load' => false,
        'save' => false
    ];

    protected function getListeners()
    {
        $listeners = [
            "modal:{$this->modal_name}:load" => "load",
            "modal:{$this->modal_name}:close" => "clear"
        ];
        return array_merge($this->listeners, $listeners);
    }

    public function updated($property)
    {
        if ($property != 'modal') {
            $this->validate();
        }
    }

    #[Computed]
    public function title()
    {
        return $this->load_state ? static::$load_title : static::$title;
    }

    #[Computed]
    public function modalName()
    {
        $exploded_name = explode(".", $this->getName());
        return $exploded_name[count($exploded_name) - 1];
    }

    public function load($id)
    {
        $this->guard($this->permission['load'] ?? false);
        $this->load_state = true;
    }

    public function save()
    {
        $this->guard($this->permission['save'] ?? false);
    }

    public function clear()
    {
        $this->load_state = false;
        $this->resetValidation();
    }

    protected function guard($guard)
    {
        if (is_bool($guard)) {
            return abort_if(!$guard, 403, 'This Action is Unauthorized');
        }
        return $this->authorize($guard);
    }
}
