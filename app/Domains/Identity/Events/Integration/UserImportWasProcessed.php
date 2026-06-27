<?php

namespace App\Domains\Identity\Events\Integration;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserImportWasProcessed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $userId,
        public string $email,
        public string $gender,
        public string $dateOfBirth,
        public string $phoneNumber,
    ) {}
}
