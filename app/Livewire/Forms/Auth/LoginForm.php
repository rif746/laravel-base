<?php

namespace App\Livewire\Forms\Auth;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\Authentication\AuthenticateUserDTO;
use App\Livewire\Concerns\Form\InteractWithDto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    use InteractWithDto;

    #[MapTo(AuthenticateUserDTO::class)]
    #[Validate('required|email')]
    public string $email = '';

    #[MapTo(AuthenticateUserDTO::class)]
    #[Validate('required|min:6')]
    public string $password = '';

    #[MapTo(AuthenticateUserDTO::class)]
    public bool $remember = false;
}
