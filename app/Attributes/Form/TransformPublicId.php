<?php

namespace App\Attributes\Form;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TransformPublicId
{
    public function __construct(
        public string $model,
        public string $publicIdColumn = 'ulid',
        public string $idColumn = 'id'
    ) {}
}
