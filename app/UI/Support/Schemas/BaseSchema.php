<?php

namespace App\UI\Support\Schemas;

interface BaseSchema
{
    /**
     * Parameterless factory method for fluent API instantiation.
     */
    public static function make(): static;

    /**
     * Pass dynamic context (e.g., form state or Livewire state) into the schema.
     */
    public function withContext(array $context): static;

    /**
     * Resolve the schema payload.
     * Returns SchemaField for individual inputs, or array<string, SchemaField> for group containers.
     */
    public function getSchema(): array|InputSchemaField;
}
