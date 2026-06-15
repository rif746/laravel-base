<?php

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Queries\GetAuthenticatedUserContext;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[On('profile-updated')]
    public function refreshProfile(): void
    {
        app(GetAuthenticatedUserContext::class)->refresh();
    }

    #[Computed]
    public function user(): ?User
    {
        return app(GetAuthenticatedUserContext::class)->fetch();
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirectRoute('login', navigate: true);
    }
};
