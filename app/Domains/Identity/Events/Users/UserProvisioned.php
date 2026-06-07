<?php

namespace App\Domains\Identity\Events\Users;

use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserProvisioned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        User $user,
    ) {}
}
