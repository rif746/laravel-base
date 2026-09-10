<?php

namespace App\Attributes\Model;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Lookupable
{
    public function __construct(
        public string $id,
        public string $text
    ){}
}
