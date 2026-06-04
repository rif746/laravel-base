<?php

namespace App\Domains\Identity\Events\Passwords;

use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPasswordReset
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Fired after a user's password has been successfully reset via the
     * password-reset link flow. Listeners can use this for audit logging,
     * security alerts, or session invalidation on other devices.
     */
    public function __construct(
        public readonly User $user,
    ) {}
}
