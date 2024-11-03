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
    protected static $title = 'locale/user.title.modal.update';

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = 'locale/user.title.modal.update';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => 'user edit',
        'save' => 'user edit',
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
        return Role::all(['name'])->toArray();
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
            $this->success(__('locale/user.alert.updated'));
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->reset();
    }
}
