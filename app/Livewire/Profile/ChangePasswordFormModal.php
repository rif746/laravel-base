<?php

namespace App\Livewire\Profile;

use App\Livewire\Module\BaseModal;
use Livewire\Attributes\Rule;
use Mary\Traits\Toast;

class ChangePasswordFormModal extends BaseModal
{
    use Toast;

    #[Rule(['required', 'current_password'], as: 'Current Password')]
    public $current_password;

    #[Rule(['required', 'confirmed'], as: 'New Password')]
    public $new_password;
    public $new_password_confirmation;

    /*
     * modal title
     * @var string
     */
    protected static $title = 'locale/profile.title.modal.change_password';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'save' => true
    ];

    public function render()
    {
        return view("livewire.profile.change-password-form-modal");
    }

    public function save()
    {
        parent::save();
        $this->validate();

        $update = auth()->user()->update([
            'password' => bcrypt($this->new_password)
        ]);

        if (!is_null($update)) {
            $this->modal = false;
            $this->success('Password changed successfully');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->current_password = "";
        $this->new_password = "";
        $this->new_password_confirmation = "";
    }
}
