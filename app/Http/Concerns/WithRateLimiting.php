<?php

namespace App\Http\Concerns;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait WithRateLimiting
{
    /**
     * Ensure the given key/request is not rate limited.
     *
     * @param string $keyIdentifier Unique key segment (e.g., email or username)
     * @param int $maxAttempts Maximum allowed attempts before throttle
     * @param string $errorField Field key used for ValidationException payload
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(
        string $keyIdentifier,
        int $maxAttempts = 5,
        string $errorField = 'email'
    ): void {
        $key = $this->throttleKey($keyIdentifier);

        if (! RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return;
        }

        // Dispatch native lockout event
        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            $errorField => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Increment the rate limiter attempts counter for the given identifier.
     *
     * @param string $keyIdentifier
     * @param int $decaySeconds Decay time in seconds for the rate limit window
     */
    protected function hitRateLimiter(string $keyIdentifier, int $decaySeconds = 60): void
    {
        RateLimiter::hit($this->throttleKey($keyIdentifier), $decaySeconds);
    }

    /**
     * Clear the rate limiter attempts counter for the given identifier.
     *
     * @param string $keyIdentifier
     */
    protected function clearRateLimiter(string $keyIdentifier): void
    {
        RateLimiter::clear($this->throttleKey($keyIdentifier));
    }

    /**
     * Generate a unique throttle key based on the identifier and request IP address.
     *
     * @param string $keyIdentifier
     * @return string
     */
    protected function throttleKey(string $keyIdentifier): string
    {
        return Str::transliterate(Str::lower($keyIdentifier).'|'.request()->ip());
    }
}
