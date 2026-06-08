<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\Identity\Actions\Passwords\SendPasswordResetLink;
use App\Domains\Identity\DTOs\Passwords\ForgotPasswordDTO;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.forgot_password.header'])]
#[Seo(title: 'domains/auth.seo.forgot_password.title', description: 'domains/auth.seo.forgot_password.description', keywords: 'domains/auth.seo.forgot_password.keywords')]
class extends Component
{
    use HasSeoAttributes, WithToast;

    public string $email;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function forgotPassword(SendPasswordResetLink $action): void
    {
        $this->validate();

        $status = $action->execute(new ForgotPasswordDTO(
            email: $this->email,
        ));

        if ($status === Password::RESET_LINK_SENT) {
            $this->success(__('Password reset link has been sent to your email'));
        }
    }
};
