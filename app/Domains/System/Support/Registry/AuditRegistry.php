<?php

namespace App\Domains\System\Support\Registry;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Relations\Relation;
use OwenIt\Auditing\AuditableObserver;
use OwenIt\Auditing\Contracts\Auditable;

class AuditRegistry
{
    /**
     * Storage for registered models.
     */
    protected static array $models = [];

    /**
     * Register a model class into the AuditRegistry.
     *
     * @param class-string<\OwenIt\Auditing\Contracts\Auditable> $model The FQCN of the Model class
     * @param string $label Human-readable label for UI select options
     * @return void
     *
     * @throws InvalidArgumentException If class does not exist or fails interface contract
     */
    public static function register(string $model, string $label): void
    {
        if (! class_exists($model)) {
            throw new InvalidArgumentException("Model class [{$model}] does not exist.");
        }

        if (! is_subclass_of($model, Auditable::class)) {
            throw new InvalidArgumentException("Model class [{$model}] must implement " . Auditable::class . " interface.");
        }

        $alias = static::resolveMorphAlias($model);
        $key = $alias ?? $model;

        static::$models[$key] = [
            'key'   => $key,
            'class' => $model,
            'label' => $label,
            'alias' => $alias,
        ];
    }

    /**
     * Get the options list for UI Select Dropdown Filter [key => label].
     */
    public static function getOptions(): array
    {
        $options = [];
        foreach (static::$models as $key => $item) {
            $options[$key] = $item['label'];
        }

        return $options;
    }

    /**
     * Get all registered models metadata.
     */
    public static function getRegisteredModels(): array
    {
        return static::$models;
    }

    /**
     * Resolve the Morph Alias from Relation::morphMap().
     */
    public static function resolveMorphAlias(string $model): ?string
    {
        $morphMap = Relation::morphMap();
        $alias = array_search($model, $morphMap, true);

        return $alias !== false ? (string) $alias : null;
    }
}
