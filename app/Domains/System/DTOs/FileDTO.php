<?php

namespace App\Domains\System\DTOs;

readonly class FileDTO
{
    public function __construct(
        public string $modelType,
        public int|string $modelId,
        public string $relationName,
        public string $disk,
        public string $directory,
        public ?array $options,
        public int|string $uploaderId,
    ) {}
}
