<?php

namespace App\DTOs\System;

use App\Enums\System\SystemSettingKey;

readonly class SystemSetingDTO
{
    public function __construct(
        public SystemSettingKey $key,
        public mixed $value,
    ) {}
}
