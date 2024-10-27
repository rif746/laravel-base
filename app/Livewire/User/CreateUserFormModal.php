<?php

namespace App\Livewire\User;

use App\Livewire\Forms\CreateUserForm;
use App\Livewire\Module\BaseModal;
use App\Models\Role;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;

class CreateUserFormModal extends BaseModal
{
    use Toast;

    public CreateUserForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = 'Create User';

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = 'Create User';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => false,
        'save' => true,
    ];

    public function mount()
    {
        $this->setId($this->modalName());
        $this->clear();
    }

    public function render()
    {
        return view('livewire.user.create-user-form-modal');
    }

    #[Computed(persist: true)]
    public function roles()
    {
        return Role::all(['name'])->pluck('name');
    }

    public function save()
    {
        parent::save();
        if ($this->form->post()) {
            $this->modal = false;
            $this->dispatch('user-table:reload');
            $this->success('User created!');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
