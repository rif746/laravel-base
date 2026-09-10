<?php

namespace App\Domains\System\Traits\Model;

use App\Attributes\Model\Translatable;
use ReflectionClass;

trait HasTranslation
{
    /**
     * Internal runtime cache to avoid Reflection overhead.
     *
     * @var array<string, array<string, string>>
     */
    protected static array $translatableFieldMapCache = [];

    /**
     * Retrieve translatable fields mapped to their respective input UI types.
     *
     * @return array<string, string>
     */
    public static function getTranslatableFieldTypes(): array
    {
        $class = static::class;

        if (isset(self::$translatableFieldMapCache[$class])) {
            return self::$translatableFieldMapCache[$class];
        }

        $reflection = new ReflectionClass($class);
        $attributes = $reflection->getAttributes(Translatable::class);

        if (! empty($attributes)) {
            /** @var Translatable $instance */
            $instance = $attributes[0]->newInstance();

            return self::$translatableFieldMapCache[$class] = $instance->fields;
        }

        return self::$translatableFieldMapCache[$class] = [];
    }

    /**
     * Get field names array for compatibility.
     *
     * @return array<string>
     */
    public static function getTranslatableFields(): array
    {
        return array_keys(static::getTranslatableFieldTypes());
    }

    /**
     * Check if a field is declared as translatable.
     */
    public function isTranslatableField(string $key): bool
    {
        return array_key_exists($key, static::getTranslatableFieldTypes());
    }
}
