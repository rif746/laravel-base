<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function notifications()
    {
        return auth('web')->user()->notifications()->latest()->get();
    }

    public function read(string $id): void
    {
        auth('web')->user()->unreadNotifications()->where('id', $id)->first()?->markAsRead();
        $this->__unset('notifications');
    }

    public function readAll(): void
    {
        auth('web')->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->__unset('notifications');
    }
};
