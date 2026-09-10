<?php

namespace App\Attributes\Model;

use Attribute;

/**
 * Metadata attribute to declare translatable JSON fields and UI input types on Models.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Translatable
{
    /**
     * @param array<string, string> $fields Format: ['title' => 'text', 'body' => 'wysiwyg', 'excerpt' => 'textarea']
     */
    public function __construct(
        public array $fields = []
    ) {}
}
