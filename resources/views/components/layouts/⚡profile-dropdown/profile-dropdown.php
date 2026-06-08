<?php

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[On('profile-updated')]
    #[Computed]
    public function user(): ?User
    {
        return auth('web')->user()->load(['profile', 'avatar']);
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirectRoute('login', navigate: true);
    }
};
