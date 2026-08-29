<?php

use App\Domains\Identity\Events\Authentication\UserLoggedOut;
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
        GetAuthenticatedUserContext::refresh();
    }

    #[Computed]
    public function user(): ?User
    {
        return GetAuthenticatedUserContext::fetch();
    }

    public function logout(): void
    {
        $request = request();
        UserLoggedOut::dispatch(user: $request->user(), ipAddress: $request->ip(), userAgent: $request->userAgent());

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirectRoute('login', navigate: true);
    }
};
