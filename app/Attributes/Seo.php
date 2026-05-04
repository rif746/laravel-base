<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Seo
{
    public function __construct(
        public string $title,
        public string $name = '',
        public string $description = '',
        public string|array $keywords = [],
        public string $image = '',
        public string $url = '',
        public string $type = 'website',
    ) {}
}
