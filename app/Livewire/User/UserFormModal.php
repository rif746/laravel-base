<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseModal;

class UserFormModal extends BaseModal
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
     * save or load permission
     * @var string|bool
     */
    protected static $permission;

    public function mount()
    {
        $this->resetModal();
    }

    public function render()
    {
        return view("livewire.user.user-form-modal");
    }

    public function clear()
    {
        $this->resetModal();
    }

    protected function onLoad($id)
    {
        // code here
    }

    protected function onSave()
    {
        // code here
    }
}
