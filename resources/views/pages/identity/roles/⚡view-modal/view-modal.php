<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Domains\Identity\Models\Role;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use WithModal;

    #[Locked]
    public ?int $id = null;

    protected string $mode = 'view';

    protected string $resourceName = 'role';

    #[Computed]
    public function role(): Role
    {
        return $this->id ? Role::with('permissions')->findOrFail($this->id) : new Role;
    }

    public function show(int|string $id): void
    {
        $this->id = $id;
    }

    public function hide(): void
    {
        $this->reset();
    }
};
