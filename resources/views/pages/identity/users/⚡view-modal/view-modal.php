<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use WithModal;

    #[Locked]
    public ?int $id = null;

    protected string $mode = 'view';

    protected string $resourceName = 'user';

    #[Computed]
    public function user(): Model|Collection|User|null
    {
        return $this->id ? User::findOrFail($this->id) : new User;
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
