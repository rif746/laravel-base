<?php

namespace App\Domains\Identity\Events\Governance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserWasActivated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $email
    ) {}
}
