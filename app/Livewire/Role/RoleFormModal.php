<?php

namespace App\Livewire\Role;

use App\Livewire\Forms\RoleForm;
use App\Livewire\Module\BaseModal;
use App\Models\Permission;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;

class RoleFormModal extends BaseModal
{
    use Toast;

    public RoleForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = 'locale/role.title.modal.create';

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = 'locale/role.title.modal.edit';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => 'role edit',
        'save' => 'role create',
    ];

    public function mount()
    {
        $this->clear();
    }

    public function render()
    {
        return view('livewire.role.role-form-modal');
    }

    #[Computed(persist: true)]
    public function permissions()
    {
        return Permission::all();
    }

    public function load($id)
    {
        parent::load($id);
        sleep(3);
        $this->form->load($id);
    }

    public function save()
    {
        parent::save();
        if ($this->form->post()) {
            $this->modal = false;
            $this->dispatch('role-table:reload');
            $this->success('Role '.($this->form->id == 0 ? 'created!' : 'updated!'));
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
