<?php

namespace App\Domains\Identity\Actions\Onboarding;

use App\Domains\Identity\Models\User;

class ResendVerificationEmail
{
    /**
     * Send the email verification notification to the given user.
     *
     * Accepts the User model explicitly instead of pulling it from request(),
     * keeping this action free of any HTTP/request dependencies.
     */
    public function execute(User $user): void
    {
        $user->sendEmailVerificationNotification();
    }
}
