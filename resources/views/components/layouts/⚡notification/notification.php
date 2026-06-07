<?php

use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function notifications(): Collection
    {
        return auth('web')->user()->unreadNotifications()->latest()->get();
    }

    public function read(string $id): void
    {
        $this->notifications->where('id', $id)->first()?->markAsRead();
        $this->__unset('notifications');
    }

    public function readAll(): void
    {
        $this->notifications->update(['read_at' => now()]);
        $this->__unset('notifications');
    }
};
