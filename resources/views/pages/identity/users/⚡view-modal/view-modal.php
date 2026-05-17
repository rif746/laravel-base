<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Models\Identity\User;
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
    public function user()
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
