<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    #[On('reload-user-info')]
    public function user()
    {
        return auth('web')->user()->load(['profile']);
    }

    public function resendVerificationEmail(): void
    {
        if ($this->user->email_verified_at) {
            $this->js("toast('info', __('The email has already been verified.')');");

            return;
        }
        $this->user->sendEmailVerificationNotification();
        $this->js("toast('success', __('The verification email has been sent.')');");
    }
};
