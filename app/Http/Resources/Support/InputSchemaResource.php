<?php

namespace App\Http\Resources\Support;

use App\UI\Enums\InputType;
use App\UI\Support\Schemas\InputSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property InputSchema $resource
 */
class InputSchemaResource extends JsonResource
{
    /**
     * Transform the InputSchema object into an array payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Extract raw Field value object from Schema
        $field = $this->resource->getSchema();

        return [
            'key' => $field->key,
            'label' => __($field->label),
            'type' => $field->type instanceof InputType ? $field->type->value : (string) $field->type,
            'default' => $field->default,
            'rules' => $field->rules,
            'is_live' => $field->isLive,
            'depends_on' => $field->dependsOn ?: null,
            'is_translatable' => property_exists($field, 'isTranslatable') ? $field->isTranslatable : false,
            'attributes' => $this->resolveAttributes($field->attributes),
        ];
    }

    /**
     * Normalize attributes and resolve closure-based options dynamically.
     *
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    protected function resolveAttributes(array $attributes): array
    {
        if (isset($attributes['options'])) {
            $options = $this->resource->resolveOptions();

            if ($options instanceof Collection) {
                $options = $options->all();
            }

            $attributes['options'] = $this->normalizeOptions($options);
        }

        return $attributes;
    }

    /**
     * Standardize options into uniform key-value dictionary for JSON serialization.
     */
    protected function normalizeOptions(mixed $options): array
    {
        if (! is_array($options)) {
            return [];
        }

        $normalized = [];

        foreach ($options as $key => $value) {
            if (is_array($value)) {
                $normalized[] = [
                    'value' => $value['value'] ?? $key,
                    'label' => __($value['label'] ?? $value['name'] ?? $value['value'] ?? $key),
                ];
            } else {
                $normalized[] = [
                    'value' => $key,
                    'label' => __((string) $value),
                ];
            }
        }

        return $normalized;
    }
}
