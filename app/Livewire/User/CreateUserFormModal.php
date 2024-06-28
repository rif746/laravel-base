<?php

namespace App\Livewire\User;

use App\Livewire\Forms\CreateUserForm;
use App\Livewire\Module\BaseModal;
use App\Livewire\Module\Trait\Notification;
use App\Models\Role;
use Livewire\Attributes\Computed;

class CreateUserFormModal extends BaseModal
{
    use Notification;
    
    public CreateUserForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = "Create User";

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = "Create User";

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => false,
        'save' => true
    ];

    public function mount()
    {
        $this->clear();
    }

    public function render()
    {
        return view("livewire.user.create-user-form-modal");
    }

    #[Computed(persist: true)]
    public function roles()
    {
        return Role::all(['name'])->pluck('name');
    }

    public function save()
    {
        parent::save();
        if($this->form->post()) {
            $this->dispatch('close-modal', name: $this->modal_name);
            $this->dispatch("user-table:reload");
            $this->toast('User created!');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
