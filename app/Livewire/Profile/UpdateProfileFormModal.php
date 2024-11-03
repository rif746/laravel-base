<?php

namespace App\Livewire\Profile;

use App\Enum\GenderType;
use App\Livewire\Forms\UpdateProfileForm;
use App\Livewire\Module\BaseModal;
use Mary\Traits\Toast;

class UpdateProfileFormModal extends BaseModal
{
    use Toast;

    public UpdateProfileForm $form;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = 'locale/profile.title.modal.update_profile';

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = 'locale/profile.title.modal.update_profile';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => true,
        'save' => true,
    ];

    public function render()
    {
        $gender = [
            [
                'id' => GenderType::MALE->value,
                'name' => GenderType::MALE->label(),
            ],
            [
                'id' => GenderType::FEMALE->value,
                'name' => GenderType::FEMALE->label(),
            ],
        ];

        return view('livewire.profile.update-profile-form-modal')
            ->with('gender', $gender);
    }

    public function load($id)
    {
        parent::load($id);
        $this->form->load($id);
        abort_if($this->form->email != auth('web')->user()->email, 404);
    }

    public function save()
    {
        parent::save();
        if (! is_null($this->form->post())) {
            $this->modal = false;
            $this->dispatch('profile:update');
            $this->success('Profile updated successfully');
        }
    }

    public function clear()
    {
        parent::clear();
        $this->form->reset();
    }
}
