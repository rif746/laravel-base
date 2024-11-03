<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateUserForm extends Form
{
    #[Validate]
    public $email = null;

    #[Validate]
    public $name = null;

    #[Validate]
    public $password = null;

    #[Validate('required', as: 'Role')]
    public $role_name = null;

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'min:5', 'unique:users,name'],
            'password' => [Password::default()],
        ];
    }

    public function post()
    {
        $this->validate();
        return User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ])->syncRoles($this->role_name);
    }
}
