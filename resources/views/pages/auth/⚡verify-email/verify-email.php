<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\Identity\Actions\Registration\ResendVerificationEmail;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.verify_email.header'])]
#[Seo(title: 'domains/auth.seo.verify_email.title', description: 'domains/auth.seo.verify_email.description', keywords: 'domains/auth.seo.verify_email.keywords')]
class extends Component
{
    use HasSeoAttributes, WithToast;

    public function mount(): void
    {
        $this->checkVerified();
    }

    public function checkVerified(): void
    {
        if (request()->user()->hasVerifiedEmail()) {
            $this->redirectRoute('dashboard');

            return;
        }
    }

    public function resendEmail(ResendVerificationEmail $action): void
    {
        $this->checkVerified();

        // The Gateway resolves the User from the session and passes it
        // explicitly — the domain action has no HTTP/request dependency.
        $action->execute(Auth::user());

        $this->success(__('domains/auth.pages.verify_email.resend_link'));
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirectRoute('login', navigate: true);
    }
};
