<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate(['required', 'email'], as: "email")]
    public $email;

    #[Validate(['required'], as: "password")]
    public $password;

    public $remember;

    public function post()
    {
        $this->validate();
        if (!auth()->attempt($this->only(['email', 'password']), $this->remember)) {
            $this->addError('password', 'incorrect email or password');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
