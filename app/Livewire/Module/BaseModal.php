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

    /*
     * normal modal title
     * @var string
     */
    protected static $title;

    /*
     * load modal title
     * @var string
     */
    protected static $load_title;

    /*
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

    public function updated()
    {
        $this->validate();
    }

    #[Computed]
    public function title()
    {
        return $this->load_state ? static::$load_title : static::$title;
    }

    #[Computed(true)]
    public function modal_name()
    {
        $exploded_name = explode(".", $this->getName());
        return $exploded_name[count($exploded_name) - 1];
    }

    public function load($id)
    {
        $this->guard($this->permission['load'] ?? false);
        $this->load_state = true;
        $this->js("visible = true");
    }

    public function save()
    {
        $this->guard($this->permission['save'] ?? false);
    }

    public function clear()
    {
        $this->load_state = false;
        $this->js("visible = false");
        $this->resetValidation();
    }

    protected function guard($guard)
    {
        if (is_bool($guard)) {
            return Gate::allowIf($guard);
        }
        return Gate::allows($guard);
    }
}
