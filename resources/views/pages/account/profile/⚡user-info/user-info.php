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
        GetAuthenticatedUserContext::refresh();
    }

    #[Computed]
    public function user(): ?User
    {
        return GetAuthenticatedUserContext::fetch();
    }

    public function resendVerificationEmail(ResendVerificationEmail $action): void
    {
        if ($this->user->email_verified_at) {
            $this->warning(__('domains/auth/messages.email_already_verified'));

            return;
        }

        $action->execute($this->user);

        $this->success(__('domains/auth/messages.verification_link_sent'));
    }
};
