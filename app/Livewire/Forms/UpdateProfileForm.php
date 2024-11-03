<?php

namespace App\Livewire\Forms;

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

    #[Validate('required', as: 'Gender')]
    public $gender = null;

    protected function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email,id,' . $this->id],
            'name' => ['required', 'min:5', 'unique:users,name,id,' . $this->id],
        ];
    }

    public function load($id)
    {
        $user = User::with('profile')->find($id);
        $this->fill($user);
        $this->fill($user->profile);
    }

    public function post()
    {
        $this->validate();
        $user = User::findOrNew($this->id);
        $update['email'] = $this->email;
        $update['name'] = $this->name;
        $user->fill($update);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->profile()->updateOrCreate([], [
            'gender' => $this->gender,
        ]);

        return $user->update();
    }
}
