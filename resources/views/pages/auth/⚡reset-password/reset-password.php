<?php

use App\Attributes\Seo;
use App\Domains\Identity\Actions\Passwords\ResetUserPassword;
use App\Domains\Identity\DTOs\Passwords\ResetPasswordDTO;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Forms\Auth\ResetPasswordForm;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.reset_password.header'])]
#[Seo(title: 'domains/auth.seo.reset_password.title', description: 'domains/auth.seo.reset_password.description', keywords: 'domains/auth.seo.reset_password.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public ResetPasswordForm $form;

    public function mount(string $token): void
    {
        $this->form->token = $token;
    }

    public function resetPassword(ResetUserPassword $action): void
    {
        $this->form->validate();

        $status = $action->execute(new ResetPasswordDTO(
            token: $this->form->token,
            email: $this->form->email,
            password: $this->form->password,
            password_confirmation: $this->form->password_confirmation,
        ));

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __('domains/auth.messages.password_reset'));
            $this->redirectRoute('login');
        } else {
            $this->addError('form.email', __('domains/auth.messages.invalid_token'));
        }
    }
};
