<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\Auth\RegisterForm;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RegisterPage extends Component
{
    public RegisterForm $form;

    #[Layout("components.layouts.guest")]
    public function render()
    {
        return view('livewire.auth.register-page');
    }

    public function register()
    {
        $this->form->post();
    }
}
