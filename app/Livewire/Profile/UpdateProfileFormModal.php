<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\UpdateUserForm;
use App\Livewire\Module\BaseModal;

class UpdateProfileFormModal extends BaseModal
{
    public UpdateUserForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = "Update Profile";

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = "Update Profile";

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => true,
        'save' => true
    ];

    public function render()
    {
        return view("livewire.profile.update-profile-form-modal");
    }

    public function load($id)
    {
        parent::load($id);
        $this->form->load($id);
        abort_if($this->form->email != auth()->user()->email, 404);
    }

    public function save()
    {
        parent::save();
        if (!is_null($this->form->post())) {
            $this->dispatch('close-modal', name: $this->modal_name);
            $this->dispatch('profile:update');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->clear();
    }
}
