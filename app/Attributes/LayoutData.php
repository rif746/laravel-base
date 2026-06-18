<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class LayoutData
{
    public function __construct(
        public ?string $header = null,
        public array $breadcrumbs = [],
        public ?string $context = null,
    ) {}
}
