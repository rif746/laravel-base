<?php

namespace App\UI\Support\Schemas;

use App\UI\Enums\InputType;

readonly class InputSchemaField
{
    public function __construct(
        public string $key,
        public string $label,
        public InputType $type,
        public array $attributes,
        public array $rules,
        public mixed $default,
        public bool $isLive,
        public string $dependsOn,
        public InputSchema $schema,
    ) {}
}
