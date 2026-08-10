<?php

namespace App\UI\Support\Schemas;

abstract class AbstractGroupSchema extends AbstractSchema
{
    /**
     * @var array<string, InputSchema>
     */
    protected array $fields = [];

    /**
     * Add one or multiple fields to the container.
     */
    public function addInput(InputSchema $input): static
    {
        $inputSchema = $input->getSchema();
        $this->fields[$inputSchema->key] = $input;

        return $this;
    }

    /**
     * Pass dynamic context down to all child fields.
     */
    public function withContext(array $context): static
    {
        parent::withContext($context);

        foreach ($this->fields as $field) {
            $field->withContext($this->context);
        }

        return $this;
    }

    /**
     * Resolve all field schemas, keyed by their input keys.
     *
     * @return array<string, InputSchemaField>
     */
    public function getSchema(): array
    {
        return array_map(function ($schema) {
            return $schema->getSchema();
        }, $this->fields);
    }

    /**
     * Extract validation rules for FormRequest or Livewire.
     *
     * @return array<string, array>
     */
    public function getValidationRules(string $prefix = ''): array
    {
        $rules = [];

        foreach ($this->getSchema() as $key => $schema) {
            $fieldKey = $prefix ? "{$prefix}.{$key}" : $key;
            $rules[$fieldKey] = $schema->rules;
        }

        return $rules;
    }

    /**
     * Extract validation rules for FormRequest or Livewire.
     *
     * @return array<string, array>
     */
    public function getValidationAttributes(string $prefix = ''): array
    {
        $rules = [];

        foreach ($this->getSchema() as $key => $schema) {
            $fieldKey = $prefix ? "{$prefix}.{$key}" : $key;
            $rules[$fieldKey] = $schema->label;
        }

        return $rules;
    }
}
