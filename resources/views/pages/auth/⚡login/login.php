<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.login.header'])]
#[Seo(title: 'domains/auth.seo.login.title', description: 'domains/auth.seo.login.description', keywords: 'domains/auth.seo.login.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember,
        )) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Regenerate the session to prevent fixation attacks.
        session()->regenerate();

        // Extract HTTP primitives here — never pass request() into an Event.
        $ipAddress = request()->ip() ?? 'Unknown';
        $userAgent = request()->userAgent() ?? 'Unknown';

        // Dispatch the event — listeners handle all side-effects asynchronously.
        UserLoggedIn::dispatch(Auth::user(), $ipAddress, $userAgent);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
};
