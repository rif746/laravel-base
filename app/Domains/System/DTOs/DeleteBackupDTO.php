<?php

namespace App\Domains\System\DTOs;

readonly class DeleteBackupDTO
{
    public function __construct(
        public string $id,
    ) {}
}
