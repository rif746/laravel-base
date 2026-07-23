<?php

namespace App\UI\Support\Settings;

use App\UI\Enums\InputType;

readonly class SettingSchema implements BaseSchema
{
    public function __construct(
        public InputType $type,
        public array $rules = ['required', 'string'],
        public mixed $default = null,
        public array $options = [],
        public array $attributes = []
    ) {}

    public static function make(InputType $type, array $rules = ['required', 'string']): self
    {
        return new self($type, $rules);
    }

    public function default(mixed $default): self
    {
        return new self($this->type, $this->rules, $default, $this->options, $this->attributes);
    }

    public function rules(array $rules): self
    {
        return new self($this->type, $rules, $this->default, $this->options, $this->attributes);
    }

    public function options(array $options): self
    {
        // If the array is simple (non-associative), we should try to localize the values if they are Enums
        // But for now, we just pass it as is, or expect the caller to handle it.
        // The common case is ['value' => 'Label']
        return new self($this->type, $this->rules, $this->default, $options, $this->attributes);
    }

    public function attributes(array $attributes): self
    {
        return new self($this->type, $this->rules, $this->default, $this->options, $attributes);
    }
}
