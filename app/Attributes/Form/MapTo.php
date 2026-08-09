<?php

namespace App\Attributes\Form;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_PROPERTY)]
class MapTo
{
    /**
     * @param  class-string  $dtoClass
     */
    public function __construct(
        public string $dtoClass,
        public ?string $field = null,
        public ?string $context = 'default'
    ) {}
}
