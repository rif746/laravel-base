<?php

namespace App\Attributes\Form;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class MapTranslation
{
    public function __construct(public string $field) {}
}
