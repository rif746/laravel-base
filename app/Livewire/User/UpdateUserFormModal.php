<?php

namespace App\Livewire\User;

use App\Livewire\Forms\UpdateUserForm;
use App\Livewire\Module\BaseModal;
use App\Models\Role;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;

class UpdateUserFormModal extends BaseModal
{
    use Toast;

    public UpdateUserForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = 'Update User';

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = 'Update User';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => true,
        'save' => true,
    ];

    public function mount()
    {
        $this->clear();
    }

    public function render()
    {
        return view('livewire.user.update-user-form-modal');
    }

    #[Computed(persist: true)]
    public function roles()
    {
        return Role::all(['name'])->pluck('name');
    }

    public function load($id)
    {
        parent::load($id);
        $this->form->load($id);
    }

    public function save()
    {
        parent::save();
        if ($this->form->post()) {
            $this->modal = false;
            $this->dispatch('user-table:reload');
            $this->success('User updated!');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
