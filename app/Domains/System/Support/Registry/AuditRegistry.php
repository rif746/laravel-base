<?php

namespace App\Domains\System\Support\Registry;

use App\Attributes\Model\Audit;
use App\Domains\System\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;

class AuditRegistry
{
    /**
     * Storage for registered audit model configurations.
     *
     * @var array<string, array<string, mixed>>
     */
    protected static array $models = [];

    /**
     * Explicitly register models for auditing.
     * Must enforce that the given model class has the #[Audit] attribute.
     *
     * @param array<int, string>|string $models Single model FQCN or array of model FQCNs
     */
    public static function register(array|string $models): void
    {
        $modelList = (array) $models;

        foreach ($modelList as $model) {
            static::registerModel($model);
        }
    }

    /**
     * Inspect and register an individual model.
     *
     * @param string $model FQCN of the target model
     */
    protected static function registerModel(string $model): void
    {
        if (! class_exists($model)) {
            throw new InvalidArgumentException("Cannot register audit for non-existing model class [{$model}].");
        }

        $reflection = new ReflectionClass($model);
        $attributes = $reflection->getAttributes(Audit::class);

        // Enforce the presence of #[Audit] attribute
        if (empty($attributes)) {
            throw new InvalidArgumentException("Model [{$model}] must be decorated with the #[Audit] attribute to be registered in AuditRegistry.");
        }

        /** @var Audit $auditAttribute */
        $auditAttribute = $attributes[0]->newInstance();

        // Attach event observer dynamically
        $model::observe(AuditObserver::class);

        // Resolve fallback label if omitted in attribute
        $label = $auditAttribute->label ?? Str::headline(class_basename($model));

        // Resolve morph alias if configured via Relation::morphMap()
        $alias = static::resolveMorphAlias($model);
        $key = $alias ?? $model;

        static::$models[$key] = [
            'key'       => $key,
            'class'     => $model,
            'label'     => $label,
            'alias'     => $alias,
            'relations' => $auditAttribute->relations,
            'only'      => $auditAttribute->only,
            'exclude'   => $auditAttribute->exclude,
            'events'    => $auditAttribute->events,
        ];
    }

    /**
     * Get tracked relations for a given model class or alias.
     *
     * @return array<int, string>
     */
    public static function getRelationsFor(string $modelOrAlias): array
    {
        $key = static::resolveMorphAlias($modelOrAlias) ?? $modelOrAlias;

        return static::$models[$key]['relations'] ?? [];
    }

    /**
     * Get key-value pair [key => label] for UI Dropdown Selectors.
     *
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        return array_map(function ($item) {
            return $item['label'];
        }, static::$models);
    }

    /**
     * Get all registered model configurations.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getRegisteredModels(): array
    {
        return static::$models;
    }

    /**
     * Resolve morph alias from an Eloquent Relation morph map.
     */
    public static function resolveMorphAlias(string $model): ?string
    {
        $morphMap = Relation::morphMap();
        $alias = array_search($model, $morphMap, true);

        return $alias !== false ? (string) $alias : null;
    }

    /**
     * Flush memory cache during the test suite's execution.
     */
    public static function flush(): void
    {
        static::$models = [];
    }
}
