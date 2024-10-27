<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateUserForm extends Form
{
    #[Locked]
    public $id;

    #[Validate('required', as: 'Email')]
    #[Validate('email', as: 'Email')]
    #[Validate('string', as: 'Email')]
    public $email;

    #[Validate('required', as: 'Name')]
    #[Validate('min:5', as: 'Name')]
    public $name;

    #[Validate('required', as: 'Gender')]
    public $gender;

    #[Validate('required', as: 'Role')]
    public $role;

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
        info($user);
        $this->id = $user->id;
        $this->email = $user->email;
        $this->name = $user->name;
        $this->gender = $user->profile->gender;
        $this->role = $user->role_name;
    }

    public function clear()
    {
        $this->id = 0;
        $this->email = "";
        $this->name = "";
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
