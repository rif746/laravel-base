<?php

use App\Domains\Identity\Models\Role;
use App\Livewire\Concerns\WithModal;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use WithModal;

    #[Locked]
    public ?int $id = null;

    #[Locked]
    public string $mode = 'view';

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
