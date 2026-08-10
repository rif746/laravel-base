<?php

namespace App\Livewire\Forms\Auth;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\Passwords\ForgotPasswordDTO;
use App\Livewire\Concerns\Form\InteractWithDto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ForgotPasswordForm extends Form
{
    use InteractWithDto;

    #[MapTo(ForgotPasswordDTO::class)]
    #[Validate(['required', 'email', 'exists:users,email'])]
    public string $email = '';
}
