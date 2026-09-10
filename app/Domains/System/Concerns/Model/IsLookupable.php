<?php

namespace App\Domains\System\Concerns\Model;

use App\Attributes\Model\Lookupable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use ReflectionClass;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait IsLookupable
{
    /**
     * Internal runtime static memory cache to avoid Reflection API overhead.
     *
     * @var array<string, ?Lookupable>
     */
    protected static array $lookupableMetadataCache = [];

    /**
     * Resolve and cache the #[Lookupable] attribute metadata.
     */
    public static function getLookupableConfig(): ?Lookupable
    {
        $class = static::class;

        if (array_key_exists($class, static::$lookupableMetadataCache)) {
            return static::$lookupableMetadataCache[$class];
        }

        $reflection = new ReflectionClass($class);
        $attributes = $reflection->getAttributes(Lookupable::class);

        if (! empty($attributes)) {
            /** @var Lookupable $instance */
            $instance = $attributes[0]->newInstance();

            return static::$lookupableMetadataCache[$class] = $instance;
        }

        return static::$lookupableMetadataCache[$class] = null;
    }

    /**
     * Retrieve the unique identifier for lookup choices.
     */
    public function getLookupId(): int|string
    {
        $config = static::getLookupableConfig();

        if ($config && isset($this->{$config->id})) {
            return $this->{$config->id};
        }

        return $this->getKey();
    }

    /**
     * Retrieve and format the display text using string template placeholders or methods.
     */
    public function getLookupText(): string
    {
        $config = static::getLookupableConfig();

        if ($config) {
            $target = $config->text;

            // 1. Template Resolver: Handles formats like ':name - :code' or ':first_name :last_name'
            if (str_contains($target, ':')) {
                return preg_replace_callback('/:([a-zA-Z0-9_]+)/', function (array $matches): string {
                    $attribute = $matches[1];

                    // Resolve custom method call on model
                    if (method_exists($this, $attribute)) {
                        return (string) $this->{$attribute}();
                    }

                    // Resolve attribute value or fallback empty
                    return (string) ($this->{$attribute} ?? '');
                }, $target);
            }

            // 2. Direct method resolution call
            if (method_exists($this, $target)) {
                return (string) $this->{$target}();
            }

            // 3. Direct attribute column resolution
            if (isset($this->{$target})) {
                return (string) $this->{$target};
            }
        }

        // Default fallback resolution chain
        if (isset($this->name)) {
            return (string) $this->name;
        }

        if (isset($this->title)) {
            return (string) $this->title;
        }

        return (string) $this->getKey();
    }

    /**
     * Intercept virtual 'id' accessor for LookupResource compatibility.
     */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getLookupId(),
        );
    }

    /**
     * Intercept virtual 'text' accessor for LookupResource compatibility.
     */
    protected function text(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getLookupText(),
        );
    }
}
