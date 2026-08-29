<?php

namespace App\Domains\Identity\Events\Authentication;

use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedOut
{

    use Dispatchable, SerializesModels;
    public function __construct(
        public readonly User $user,
        public readonly string $ipAddress,
        public readonly string $userAgent,
    ) {}
}
