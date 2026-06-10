<?php

use App\Domains\Identity\Models\User;
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

    protected string $resourceName = 'user';

    #[Computed]
    public function user(): ?User
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
