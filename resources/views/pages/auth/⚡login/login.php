<?php

use App\Attributes\Ui\Seo;
use App\Domains\Identity\Actions\Authentication\AuthenticateUser;
use App\Domains\Identity\DTOs\Authentication\AuthenticateUserDTO;
use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use App\Http\Concerns\WithRateLimiting;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Forms\Auth\LoginForm;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth/pages.login.header'])]
#[Seo(title: 'domains/auth/seo.login.title', description: 'domains/auth/seo.login.description', keywords: 'domains/auth/seo.login.keywords')]
class extends Component
{
    use HasSeoAttributes;
    use WithRateLimiting;

    public LoginForm $form;


    public function login(AuthenticateUser $authenticateUser): void
    {
        $this->form->validate();

        // 1. Rate Limit Check (Targeting 'form.email' for a Livewire error bag)
        $this->ensureIsNotRateLimited(
            keyIdentifier: $this->form->email,
            errorField: 'form.email'
        );

        try {
            $authenticateUser->execute($this->form->toDto());
        } catch (InvalidArgumentException|DomainException $e) {
            // Hit limiter on authentication failure
            $this->hitRateLimiter($this->form->email);

            throw ValidationException::withMessages([
                'form.email' => $e->getMessage(),
            ]);
        }

        // Clear limiter on success
        $this->clearRateLimiter($this->form->email);

        session()->regenerate();

        $navigate = session()->get('url.intended') != url('/docs/api');

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: $navigate);
    }
};
