<?php

namespace App\Livewire\User;

use App\Livewire\Forms\UpdateUserForm;
use App\Livewire\Module\BaseModal;
use App\Livewire\Module\Trait\Notification;

class UpdateUserFormModal extends BaseModal
{
    use Notification;

    public UpdateUserForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = "Update User";

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
        return view("livewire.user.update-user-form-modal");
    }

    public function load($id)
    {
        parent::load($id);
        $this->form->load($id);

    }

    public function save()
    {
        parent::save();
        if($this->form->post()) {
            $this->dispatch('close-modal', name: $this->modal_name);
            $this->dispatch("user-table:reload");
            $this->toast('User updated!');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
