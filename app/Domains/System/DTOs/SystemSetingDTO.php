<?php

namespace App\Domains\System\DTOs;

use App\Domains\System\Enums\SystemSettingKey;

readonly class SystemSetingDTO
{
    public function __construct(
        public SystemSettingKey $key,
        public mixed $value,
    ) {}
}
