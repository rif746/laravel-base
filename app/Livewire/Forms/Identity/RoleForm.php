<?php

namespace App\Livewire\Forms\Identity;

use App\Attributes\Form\MapTo;
use App\Domains\Identity\DTOs\AccessControl\CreateRoleDTO;
use App\Domains\Identity\DTOs\AccessControl\UpdateRoleDTO;
use App\Livewire\Concerns\Form\InteractWithDto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    use InteractWithDto;

    #[MapTo(CreateRoleDTO::class, context: 'create')]
    #[Validate('required')]
    public string $name = '';

    #[MapTo(CreateRoleDTO::class, context: 'create')]
    #[Validate('required')]
    public string $guard_name = '';

    #[MapTo(CreateRoleDTO::class, field: 'permissions', context: 'create')]
    #[MapTo(UpdateRoleDTO::class, field: 'permissions', context: 'update')]
    #[Validate('required|array')]
    public array $selected_permissions = [];
}
