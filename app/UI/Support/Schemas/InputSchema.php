<?php

namespace App\UI\Support\Schemas;

use App\UI\Enums\InputType;
use Closure;
use Illuminate\Support\Collection;

class InputSchema extends AbstractSchema
{
    protected string $key = '';

    protected string $label = '';

    protected InputType $type = InputType::TEXTLINE;

    protected mixed $default = null;

    protected array|Closure $options = [];

    protected array $attributes = [];

    protected array $rules = ['nullable', 'string'];

    protected string $depends = '';

    protected array $contextKeys = [];

    protected bool $live = false;

    /**
     * Set the field unique key / name.
     */
    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the display label.
     */
    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function type(InputType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function default(mixed $default): static
    {
        $this->default = $default;

        return $this;
    }

    public function rules(array|string $rules): static
    {
        $this->rules = is_array($rules) ? $rules : explode('|', $rules);

        return $this;
    }

    public function options(array|Closure $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function attributes(array $attributes): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function setContextKeys(array|string $keys): static
    {
        $this->contextKeys = (array) $keys;

        return $this;
    }

    public function select2(array $config = []): static
    {
        return $this->attributes(['x-select2' => json_encode($config)]);
    }

    public function live(): static
    {
        $this->live = true;

        return $this;
    }

    public function dependsOn(string $depends): static
    {
        $this->depends = $depends;

        return $this;
    }

    public function isLive(): bool
    {
        return $this->live;
    }

    public function resolveOptions(array $extraContext = []): array|Collection
    {
        if (! $this->options instanceof Closure) {
            return $this->options;
        }

        $mergedContext = array_merge($this->context, $extraContext);

        if (! empty($this->contextKeys)) {
            $mergedContext = array_intersect_key(
                $mergedContext,
                array_flip($this->contextKeys)
            );
        }

        return ($this->options)($mergedContext);
    }

    private function getAttributes(): array
    {
        $attributes = array_merge(['label' => $this->label], $this->attributes);

        if ($this->type->isSelect()) {
            $attributes['options'] = $this->resolveOptions();
        }

        return $attributes;
    }

    public function getSchema(): InputSchemaField
    {
        return new InputSchemaField(
            key: $this->key,
            label: $this->label,
            type: $this->type,
            attributes: $this->getAttributes(),
            rules: $this->rules,
            default: $this->default,
            isLive: $this->isLive(),
            dependsOn: $this->depends,
            schema: $this,
        );
    }
}
