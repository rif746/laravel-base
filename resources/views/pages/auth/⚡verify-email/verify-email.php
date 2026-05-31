<?php

use App\Domains\Auth\Actions\ResendVerificationEmail;
use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.verify_email.header'])]
#[Seo(title: 'domains/auth.seo.verify_email.title', description: 'domains/auth.seo.verify_email.description', keywords: 'domains/auth.seo.verify_email.keywords')]
class extends Component
{
    use HasSeoAttributes;

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

        $action->execute();

        $this->js("toast('".__('domains/auth.pages.verify_email.resend_link')."', 'success')");
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirectRoute('login', navigate: true);
    }
};
