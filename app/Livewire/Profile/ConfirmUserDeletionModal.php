<?php

namespace App\Livewire\Profile;

use App\Livewire\Module\BaseModal;
use Livewire\Attributes\Rule;
use Mary\Traits\Toast;

class ConfirmUserDeletionModal extends BaseModal
{
    use Toast;

    #[Rule(['required', 'current_password'], as: 'Current Password')]
    public $current_password;

    protected static $title = 'locale/profile.title.modal.user_deletion';

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
            $this->modal = false;
            $this->success('Your user deleted successfully');
            auth()->logout();
        }
    }

    public function clear()
    {
        parent::clear();
        $this->current_password = "";
    }
}
