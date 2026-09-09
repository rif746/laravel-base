<?php

namespace App\Attributes\Model;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Audit
{
    /**
     * Create a new Audit attribute instance.
     *
     * @param string|null $label Human readable label for UI selectors
     * @param array<int, string> $relations Related models to track (e.g. ['profile'])
     * @param array<int, string> $only Specific attributes to audit (empty means all)
     * @param array<int, string> $exclude Attributes to exclude from auditing
     * @param array<int, string> $events Specify event to audit (e.g. ['create', 'update', 'delete'])
     */
    public function __construct(
        public ?string $label = null,
        public array $relations = [],
        public array $only = [],
        public array $exclude = [],
        public array $events = [],
    ) {}
}
