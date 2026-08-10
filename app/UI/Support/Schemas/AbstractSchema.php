<?php

namespace App\UI\Support\Schemas;

abstract class AbstractSchema implements BaseSchema
{
    protected array $context = [];

    /**
     * Parameterless default instantiation.
     */
    public static function make(): static
    {
        return new static;
    }

    public function withContext(array $context): static
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }

    abstract public function getSchema(): array|InputSchemaField;
}
