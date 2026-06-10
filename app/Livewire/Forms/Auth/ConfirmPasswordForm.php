<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ConfirmPasswordForm extends Form
{
    #[Validate('required|string|current_password')]
    public string $password = '';
}
