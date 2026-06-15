<?php

namespace App\Domains\Identity\Actions\Onboarding;

use App\Domains\Identity\Events\Onboarding\UserEmailWasVerified;
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
     *  - App\Domains\Identity\Events\Onboarding\UserEmailWasVerified — the
     *    domain event for application-specific listeners (role grants, etc.).
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
        UserEmailWasVerified::dispatch($user);

        return true;
    }
}
