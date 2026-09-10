<?php

namespace App\Domains\System\Traits\Model;

use App\Attributes\Model\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Trait HasSlug
 *
 * Automatically generates unique slugs during the model saving event based on #[Sluggable] metadata.
 *
 * @mixin Model
 */
trait HasSlug
{
    /**
     * Static runtime cache to prevent repeated Reflection API calls per class instance.
     *
     * @var array<string, ?Sluggable>
     */
    protected static array $sluggableMetadataCache = [];

    /**
     * Intercept the standard Eloquent model booting pipeline.
     */
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model) {
            /** @var HasSlug $model */
            $model->processSlugGeneration();
        });
    }

    /**
     * Resolve #[Sluggable] Attribute configuration using static reflection caching.
     */
    public static function getSluggableConfig(): ?Sluggable
    {
        $class = static::class;

        if (array_key_exists($class, self::$sluggableMetadataCache)) {
            return self::$sluggableMetadataCache[$class];
        }

        $reflection = new ReflectionClass($class);
        $attributes = $reflection->getAttributes(Sluggable::class);

        if (! empty($attributes)) {
            /** @var Sluggable $instance */
            $instance = $attributes[0]->newInstance();

            return self::$sluggableMetadataCache[$class] = $instance;
        }

        return self::$sluggableMetadataCache[$class] = null;
    }

    /**
     * Execute slug generation logic before saving to database.
     */
    protected function processSlugGeneration(): void
    {
        $config = static::getSluggableConfig();

        if (! $config) {
            return;
        }

        $sourceField = $config->source;
        $targetField = $config->target;

        // Generate slug only if target is empty or source attribute was modified
        if (empty($this->{$targetField}) || $this->isDirty($sourceField)) {
            $sourceValue = (string) ($this->{$sourceField} ?? '');

            if (blank($sourceValue)) {
                return;
            }

            $this->{$targetField} = $config->unique
                ? $this->generateUniqueSlug($sourceValue, $config)
                : Str::slug($sourceValue, $config->separator);
        }
    }

    /**
     * Generate unique slug by checking database collisions and appending index suffix.
     */
    protected function generateUniqueSlug(string $sourceValue, Sluggable $config): string
    {
        $slug = Str::slug($sourceValue, $config->separator);
        $originalSlug = $slug;
        $count = 1;

        $targetField = $config->target;
        $keyName = $this->getKeyName();
        $keyValue = $this->getKey();

        while (static::query()
            ->where($targetField, $slug)
            ->where($keyName, '!=', $keyValue ?? 0)
            ->exists()
        ) {
            $slug = "{$originalSlug}{$config->separator}{$count}";
            $count++;
        }

        return $slug;
    }
}
