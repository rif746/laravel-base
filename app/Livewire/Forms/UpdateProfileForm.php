<?php

namespace App\Livewire\Forms;

use App\Enum\GenderType;
use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateProfileForm extends Form
{
    #[Locked]
    public $id = null;

    #[Validate('required', as: 'Email')]
    #[Validate('email', as: 'Email')]
    #[Validate('string', as: 'Email')]
    public $email = null;

    #[Validate('required', as: 'Name')]
    #[Validate('min:5', as: 'Name')]
    public $name = null;

    #[Validate('required', as: 'Biodata')]
    #[Validate('string', as: 'Biodata')]
    public $bio = null;

    #[Validate('required', as: 'Gender')]
    public $gender = null;

    protected function rules()
    {
        return [
            'email' => ['unique:users,email,id,'.$this->id],
            'name' => ['unique:users,name,id,'.$this->id],
            'gender' => ['in:'.implode(',', GenderType::values())],
        ];
    }

    public function load($id)
    {
        $user = User::find($id);
        $this->fill($user);
    }

    public function post()
    {
        $this->validate();
        $user = User::findOrNew($this->id);
        $user->fill($this->all());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return $user->update();
    }
}
