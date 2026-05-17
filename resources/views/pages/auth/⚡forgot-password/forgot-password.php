<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.forgot_password.header'])]
#[Seo(title: 'domains/auth.seo.forgot_password.title', description: 'domains/auth.seo.forgot_password.description', keywords: 'domains/auth.seo.forgot_password.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $email;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function forgotPassword(): void
    {
        $this->validate();
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            $this->js("window.toast('Password reset link has been sent to your email', 'success');");
        }
    }
};
