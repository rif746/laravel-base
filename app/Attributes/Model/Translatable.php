<?php

namespace App\Attributes\Model;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Translatable
{
    public function __construct(public array $fields = []) {}
}
