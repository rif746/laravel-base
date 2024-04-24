<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseModal;
use App\Livewire\Forms\UserForm;

class UserFormModal extends BaseModal
{
    public UserForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = "Add New User";

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = "Update User";

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => true,
        'save' => true
    ];

    public function mount()
    {
        $this->clear();
    }

    public function render()
    {
        return view("livewire.user.user-form-modal");
    }

    public function load($id)
    {
        parent::load($id);
        $this->form->load($id);
    }

    public function save()
    {
        parent::save();
        if (!is_null($this->form->post())) {
            $this->dispatch('close-modal', name: $this->modal_name);
            $this->dispatch('user-table:reload');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
