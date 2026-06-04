<?php

namespace App\Domains\Identity\Events\Registration;

use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEmailVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Fired after a user's email address has been successfully marked as
     * verified for the first time. Listeners can use this for onboarding
     * flows, role assignment, or welcome notifications.
     */
    public function __construct(
        public readonly User $user,
    ) {}
}
