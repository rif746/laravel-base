<?php

namespace App\Attributes\Form;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MapNested
{
    public function __construct(
        public string $dtoClass,
        public bool $isArray = false
    ) {}
}
