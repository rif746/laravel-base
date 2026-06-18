<?php

use App\Domains\Identity\Actions\Onboarding\ResendVerificationEmail;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Queries\GetAuthenticatedUserContext;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    use WithToast;

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

    public function resendVerificationEmail(ResendVerificationEmail $action): void
    {
        if ($this->user->email_verified_at) {
            $this->warning(__('The email has already been verified.'));

            return;
        }

        $action->execute($this->user);

        $this->success(__('The verification email has been sent.'));
    }
};
