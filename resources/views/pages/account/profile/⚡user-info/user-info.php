<?php

use App\Domains\Account\Actions\ResendVerificationEmail;
use App\Domains\Identity\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    #[On('reload-user-info')]
    public function user(): ?User
    {
        return auth('web')->user()->load(['profile']);
    }

    public function resendVerificationEmail(ResendVerificationEmail $action): void
    {
        if ($this->user->email_verified_at) {
            $this->js("toast('info', __('The email has already been verified.')')");

            return;
        }

        $action->execute();

        $this->js("toast('success', __('The verification email has been sent.')')");
    }
};
