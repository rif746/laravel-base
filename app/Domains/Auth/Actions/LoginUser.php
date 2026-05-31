<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\LoginUserDTO;
use App\Domains\Auth\Notifications\SignInActivity;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginUser
{
    /**
     * @throws ValidationException
     */
    public function execute(LoginUserDTO $dto): void
    {
        $this->ensureIsNotRateLimited($dto);

        if (! Auth::attempt(['email' => $dto->email, 'password' => $dto->password], $dto->remember)) {
            RateLimiter::hit($this->throttleKey($dto));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($dto));

        Auth::user()->notify(new SignInActivity(request()->ip(), request()->userAgent()));
    }

    /**
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(LoginUserDTO $dto): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($dto), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($dto));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(LoginUserDTO $dto): string
    {
        return Str::transliterate(Str::lower($dto->email).'|'.request()->ip());
    }
}
