<?php

namespace App\Attributes;

use Attribute;


#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class Seo
{
    public function __construct(
        public string $title,
        public string $name = '',
        public string $description = '',
        public string|array $keywords = [],
        public string $image = '',
        public string $url = '',
        public string $type = 'website',
        public string $context = '',
    ) {}
}
