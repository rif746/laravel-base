<?php

namespace App\Domains\Identity\DTOs\Authentication;

/**
 * Data Transfer Object containing authenticated user credentials and request metadata.
 */
readonly class AuthenticateUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
        public string $ipAddress = 'Unknown',
        public string $userAgent = 'Unknown',
    ) {}
}
