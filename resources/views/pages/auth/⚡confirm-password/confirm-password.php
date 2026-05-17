<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.confirm_password.header'])]
#[Seo(title: 'domains/auth.seo.confirm_password.title', description: 'domains/auth.seo.confirm_password.description', keywords: 'domains/auth.seo.confirm_password.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $password;

    public function confirmPassword()
    {
        $this->validate([
            'password' => 'required|string|current_password',
        ]);

        session()->put('auth.password_confirmed_at', time());

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
};
