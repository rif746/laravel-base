<?php

use App\Actions\Auth\ResetUserPassword;
use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\DTOs\Auth\ResetPasswordDTO;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.reset_password.header'])]
#[Seo(title: 'domains/auth.seo.reset_password.title', description: 'domains/auth.seo.reset_password.description', keywords: 'domains/auth.seo.reset_password.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $token;

    #[Url('email')]
    public string $email;

    public string $password;

    public string $password_confirmation;

    public function resetPassword(ResetUserPassword $action): void
    {
        $this->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = $action->execute(new ResetPasswordDTO(
            token: $this->token,
            email: $this->email,
            password: $this->password,
            password_confirmation: $this->password_confirmation,
        ));

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __('domains/auth.messages.password_reset'));
            $this->redirectRoute('login');
        } else {
            $this->addError('email', __('domains/auth.messages.invalid_token'));
        }
    }
};
