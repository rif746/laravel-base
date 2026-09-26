<?php

namespace App\Http\Concerns;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait WithRateLimiting
{
    /**
     * Ensure the given key/request is not rate limited across dual-layer security boundaries.
     *
     * @param string $keyIdentifier Unique key segment (e.g., email or username)
     * @param int $maxPerIpAttempts Maximum allowed attempts for the IP + Identifier layer
     * @param int $maxGlobalAttempts Maximum allowed attempts globally for the Identifier layer
     * @param string $errorField Field key used for ValidationException payload
     * @return void
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(
        string $keyIdentifier,
        int $maxPerIpAttempts = 5,
        int $maxGlobalAttempts = 10,
        string $errorField = 'email'
    ): void {
        $ipKey = $this->throttleKey($keyIdentifier);
        $globalKey = $this->globalThrottleKey($keyIdentifier);

        // 1. Check IP + Identifier Rate Limit Boundary
        if (RateLimiter::tooManyAttempts($ipKey, $maxPerIpAttempts)) {
            $this->fireLockoutAndThrowException($ipKey, $errorField);
        }

        // 2. Check Global Identifier Rate Limit Boundary (Defeats Rotating Proxies)
        if (RateLimiter::tooManyAttempts($globalKey, $maxGlobalAttempts)) {
            $this->fireLockoutAndThrowException($globalKey, $errorField);
        }
    }

    /**
     * Increment the rate limiter attempts counter for both IP-bound and global keys.
     *
     * @param string $keyIdentifier
     * @param int $decaySeconds Decay time in seconds for the rate limit window
     * @return void
     */
    protected function hitRateLimiter(string $keyIdentifier, int $decaySeconds = 60): void
    {
        RateLimiter::hit($this->throttleKey($keyIdentifier), $decaySeconds);
        RateLimiter::hit($this->globalThrottleKey($keyIdentifier), $decaySeconds);
    }

    /**
     * Clear the rate limiter attempts counter for both IP-bound and global keys.
     *
     * @param string $keyIdentifier
     * @return void
     */
    protected function clearRateLimiter(string $keyIdentifier): void
    {
        RateLimiter::clear($this->throttleKey($keyIdentifier));
        RateLimiter::clear($this->globalThrottleKey($keyIdentifier));
    }

    /**
     * Generate a unique throttle key combining identifier and request IP address.
     *
     * @param string $keyIdentifier
     * @return string
     */
    protected function throttleKey(string $keyIdentifier): string
    {
        return Str::transliterate(Str::lower(trim($keyIdentifier)) . '|' . request()->ip());
    }

    /**
     * Generate a global throttle key scoped strictly to the target identifier.
     *
     * @param string $keyIdentifier
     * @return string
     */
    protected function globalThrottleKey(string $keyIdentifier): string
    {
        return Str::transliterate('global_identity:' . Str::lower(trim($keyIdentifier)));
    }

    /**
     * Dispatch a native Lockout event and throw formatted ValidationException.
     *
     * @param string $key
     * @param string $errorField
     * @return void
     * @throws ValidationException
     */
    protected function fireLockoutAndThrowException(string $key, string $errorField): void
    {
        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            $errorField => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
