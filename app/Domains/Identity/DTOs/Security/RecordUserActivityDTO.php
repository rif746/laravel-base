<?php

namespace App\Domains\Identity\DTOs\Security;

readonly class RecordUserActivityDTO
{
    public function __construct(
        public int $userId,
        public string $event,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
        public ?array $payload = [],
    ) {}
}
