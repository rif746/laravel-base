<?php

namespace App\Domains\System\Support\Registry;

use App\Attributes\Model\ModelAttribute;
use Illuminate\Database\Eloquent\Model;
use ReflectionAttribute;
use ReflectionClass;

class ModelAttributeRegistry
{
    /**
     * Internal in-memory cache for resolved model attributes.
     *
     * @var array<string, array<ModelAttribute>>
     */
    protected static array $cache = [];

    /**
     * Dispatch matching attributes attached to the given Eloquent model.
     *
     * @param Model $model
     * @param string $hook Clean lifecycle hook name (e.g., 'saving', 'saved', 'deleting')
     * @param mixed $payload Optional payload passed alongside the event
     * @throws \ReflectionException
     */
    public function dispatch(Model $model, string $hook, mixed $payload = null): void
    {
        $modelClass = get_class($model);

        // Resolve attribute instances from memory cache or via Reflection API
        $attributes = static::$cache[$modelClass] ??= $this->resolveAttributes($modelClass);

        foreach ($attributes as $attribute) {
            if ($attribute->shouldExecute($hook)) {
                $attribute->execute($model, $hook, $payload);
            }
        }
    }

    /**
     * Resolve and instantiate attributes attached to the given model class.
     *
     * @param string $modelClass
     * @return array<ModelAttribute>
     * @throws \ReflectionException
     */
    protected function resolveAttributes(string $modelClass): array
    {
        $reflection = new ReflectionClass($modelClass);
        $attributes = $reflection->getAttributes(ModelAttribute::class, ReflectionAttribute::IS_INSTANCEOF);

        $instances = [];
        foreach ($attributes as $attribute) {
            $instances[] = $attribute->newInstance();
        }

        return $instances;
    }

    /**
     * Clear the static memory cache.
     * Useful for isolation during testing suites.
     */
    public static function flushCache(): void
    {
        static::$cache = [];
    }
}
