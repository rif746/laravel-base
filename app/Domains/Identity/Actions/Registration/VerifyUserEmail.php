<?php

namespace App\Domains\Identity\Actions\Registration;

use App\Domains\Identity\Events\Registration\UserEmailVerified;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Verified;

class VerifyUserEmail
{
    /**
     * Mark the user's email as verified and dispatch events.
     *
     * This is the ONLY place allowed to call markEmailAsVerified().
     * Returns true if the email was freshly verified (i.e. was not already
     * verified), so the Gateway can decide on the correct redirect.
     *
     * Fires two events:
     *  - Illuminate\Auth\Events\Verified — preserves Laravel's built-in
     *    verification contract for any framework listeners.
     *  - App\Domains\Identity\Events\UserEmailVerified — the domain event
     *    for application-specific listeners (onboarding, role grants, etc.).
     */
    public function execute(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        $user->markEmailAsVerified();

        // Framework event: preserves Laravel's Verified contract.
        event(new Verified($user));

        // Domain event: for application-level listeners.
        UserEmailVerified::dispatch($user);

        return true;
    }
}
