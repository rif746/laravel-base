<?php

use App\Attributes\Ui\Seo;
use App\Domains\Identity\Actions\Passwords\ResetUserPassword;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Forms\Auth\ResetPasswordForm;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth/pages.reset_password.header'])]
#[Seo(title: 'domains/auth/seo.reset_password.title', description: 'domains/auth/seo.reset_password.description', keywords: 'domains/auth/seo.reset_password.keywords')]
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

        $status = $action->execute($this->form->toDto());

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __('domains/auth/messages.password_reset'));
            $this->redirectRoute('login');
        } else {
            $this->addError('form.email', __('domains/auth/messages.invalid_token'));
        }
    }
};
