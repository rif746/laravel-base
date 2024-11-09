<?php

namespace App\Livewire\Auth;

use App\Livewire\Attributes\Metadata;
use App\Livewire\Forms\Auth\RegisterForm;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Metadata('Register')]
class RegisterPage extends Component
{
    public RegisterForm $form;

    #[Layout("components.layouts.auth")]
    public function render()
    {
        return view('livewire.auth.register-page');
    }

    public function register()
    {
        $this->form->post();
    }
}
