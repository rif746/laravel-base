<?php

namespace App\Livewire\Auth;

use App\Livewire\Attributes\Metadata;
use App\Livewire\Forms\Auth\LoginForm;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Metadata('Login')]
class LoginPage extends Component
{
    public LoginForm $form;

    #[Layout("components.layouts.auth")]
    public function render()
    {
        return view("livewire.auth.login-page");
    }

    public function send()
    {
        $this->form->post();
    }
}
