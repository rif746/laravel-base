<?php

namespace App\Livewire\Profile;

use App\Livewire\Module\BaseModal;
use Livewire\Attributes\Rule;

class ConfirmUserDeletionModal extends BaseModal
{
    #[Rule(['required', 'current_password'], as: 'Current Password')]
    public $current_password;

    protected static $title = "Confirm Account Deletion";

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'save' => true
    ];

    public function render()
    {
        return view("livewire.profile.confirm-user-deletion-modal");
    }

    public function load($id)
    {
        parent::load($id);
        $this->form->load($id);
    }

    public function save()
    {
        parent::save();
        $this->validate();
        if (auth()->user()->delete()) {
            $this->dispatch('close-modal', name: $this->modal_name);
            auth()->logout();
        }
    }

    public function clear()
    {
        parent::clear();
        $this->current_password = "";
    }
}
