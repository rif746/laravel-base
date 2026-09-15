<?php

namespace App\Domains\Identity\DTOs\Authentication;

/**
 * Data Transfer Object for API authentication including client device identification.
 */
readonly class IssueApiTokenDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $deviceName = 'api_client',
        public string $ipAddress = 'Unknown',
        public string $userAgent = 'Unknown',
    ) {}
}
