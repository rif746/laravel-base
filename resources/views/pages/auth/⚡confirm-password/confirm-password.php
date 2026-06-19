<?php

use App\Attributes\Seo;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Forms\Auth\ConfirmPasswordForm;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth/pages.confirm_password.header'])]
#[Seo(title: 'domains/auth/seo.confirm_password.title', description: 'domains/auth/seo.confirm_password.description', keywords: 'domains/auth/seo.confirm_password.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public ConfirmPasswordForm $form;

    public function confirmPassword(): void
    {
        $this->form->validate();

        session()->put('auth.password_confirmed_at', time());

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
};
