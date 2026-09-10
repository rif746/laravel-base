<?php

namespace App\Attributes\Model;

use Attribute;

/**
 * Metadata attribute to configure dynamic slug generation rules on Eloquent Models.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Sluggable
{
    /**
     * @param string $source Column name used as source for slug text generation (e.g., 'title', 'name')
     * @param string $target Target column name where the slug will be stored (default: 'slug')
     * @param string $separator Character separator used between words (default: '-')
     * @param bool $unique Whether to append numeric suffix if duplicate slug exists in database (default: true)
     */
    public function __construct(
        public string $source = 'title',
        public string $target = 'slug',
        public string $separator = '-',
        public bool $unique = true
    ) {}
}
