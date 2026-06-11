<?php

namespace App\Domains\Identity\Events\Onboarding;

use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserWasProvisioned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {}
}
