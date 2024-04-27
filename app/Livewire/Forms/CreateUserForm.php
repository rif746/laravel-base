<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateUserForm extends Form
{
    #[Validate]
    public $email;

    #[Validate]
    public $name;

    #[Validate]
    public $password;

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'min:5', 'unique:users,name'],
            'password' => [Password::default()],
        ];
    }

    public function clear()
    {
        $this->email = "";
        $this->name = "";
        $this->password = "";
    }

    public function post()
    {
        $this->validate();
        return User::create($this->all());
    }
}
