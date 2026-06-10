<?php

namespace App\Livewire\Forms\Identity;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    #[Validate('required')]
    public string $name = '';

    #[Validate('required')]
    public string $guard_name = '';

    #[Validate('required|array')]
    public array $selected_permissions = [];
}
