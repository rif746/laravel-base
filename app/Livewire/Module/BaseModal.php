<?php

namespace App\Livewire\Module;

use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BaseModal extends Component
{
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
     * @var string
     */
    protected static $modal_name;

    /*
     * @var string
     */
    protected static $load_state;

    /*
     * save or load permission
     * @var string|bool
     */
    protected static $permission;

    #[Computed(true)]
    public function title()
    {
        return static::$load_state ? static::$load_title : static::$title;
    }

    public function save()
    {
        $this->guard(self::$permission);
        $this->onSave();
    }

    protected function guard($guard)
    {
        if (is_bool($guard)) {
            return Gate::allowIf($guard);
        }
        return Gate::allows($guard);
    }

    protected function resetModal()
    {
        self::$load_state = false;
        $this->resetErrorBag();
    }

    protected function onLoad(...$param)
    {
        // code here
    }

    protected function onSave()
    {
        // code here
    }
}
