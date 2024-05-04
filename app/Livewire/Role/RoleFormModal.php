<?php

namespace App\Livewire\Role;

use App\Livewire\Forms\RoleForm;
use App\Livewire\Module\BaseModal;
use App\Livewire\Module\Trait\Notification;
use App\Models\Permission;
use Livewire\Attributes\Computed;

class RoleFormModal extends BaseModal
{
    use Notification;

    public RoleForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = "Add New Role";

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = "Update Role";

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => 'role edit',
        'save' => 'role create'
    ];

    public function mount()
    {
        $this->clear();
    }

    public function render()
    {
        return view("livewire.role.role-form-modal");
    }

    #[Computed(persist: true)]
    public function permissions()
    {
        return Permission::all();
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
            $this->dispatch('role-table:reload');
            $this->toast('Role ' . ($this->form->id == 0 ? 'created!' : 'updated!'));
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
