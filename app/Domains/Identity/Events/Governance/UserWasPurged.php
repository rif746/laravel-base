<?php

namespace App\Domains\Identity\Events\Governance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserWasPurged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $email,
        public string $model,
        public string $user_id
    ) {}
}
