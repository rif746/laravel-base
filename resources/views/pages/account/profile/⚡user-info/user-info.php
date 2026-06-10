<?php

use App\Domains\Identity\Actions\Registration\ResendVerificationEmail;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    use WithToast;
    #[On('profile-updated')]
    #[Computed]
    public function user(): ?User
    {
        return auth('web')->user()->load(['profile', 'avatar']);
    }

    public function resendVerificationEmail(ResendVerificationEmail $action): void
    {
        if ($this->user->email_verified_at) {
            $this->warning(__('The email has already been verified.'));

            return;
        }

        $action->execute();

        $this->success(__('The verification email has been sent.'));
    }
};
