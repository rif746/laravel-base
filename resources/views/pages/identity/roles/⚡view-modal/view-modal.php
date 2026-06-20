<?php

use App\Domains\Identity\Models\Role;
use App\Livewire\Concerns\WithModal;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Contracts\Role as SpatieRole;

new class extends Component
{
    use WithModal;

    #[Locked]
    public ?string $id = null;

    #[Locked]
    public string $mode = 'view';

    protected string $resourceName = 'role';

    #[Computed]
    public function role(): SpatieRole
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
