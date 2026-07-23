<?php

namespace App\Domains\System\Traits\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @mixin Model
 */
trait HasTranslation
{
    /**
     * Internal cache storage for translations retrieved from the DB table.
     * Format: ['en' => ['name' => 'Value', 'story' => '...'], 'ar' => [...]]
     */
    protected array $translationsCache = [];

    /**
     * Tracks whether translation records have been read from the database table for this instance.
     */
    protected bool $translationsLoaded = false;

    /**
     * Intercept the standard Eloquent model booting pipeline.
     */
    public static function bootHasTranslation(): void
    {
        // Automatically save any dirty or staged translations when the parent model saves
        static::saved(function (self $model) {
            $model->saveTranslations();
        });

        // Automatically drop related database rows when the parent record is deleted
        static::deleted(function (self $model) {
            $model->purgeTranslations();
        });
    }

    /**
     * Magic Getter Interception:
     * Intercepts access to properties defined in the $translatable array.
     */
    public function __get($key)
    {
        if ($this->isTranslationAttribute($key)) {
            return $this->translate($key, $this->getLocale());
        }

        return parent::__get($key);
    }

    /**
     * Magic Setter Interception:
     * Intercepts updates to properties defined in the $translatable array.
     */
    public function __set($key, $value)
    {
        if ($this->isTranslationAttribute($key)) {
            $this->setTranslationValue($key, $this->getLocale(), $value);

            return;
        }

        parent::__set($key, $value);
    }

    /**
     * Magic Isset Interception:
     * Handles isset() evaluations for translatable fields.
     */
    public function __isset($key)
    {
        if ($this->isTranslationAttribute($key)) {
            return ! is_null($this->translate($key, $this->getLocale()));
        }

        return parent::__isset($key);
    }

    /**
     * Override the standard Eloquent fill process to support nested language arrays
     * Example: $model->fill(['en' => ['name' => 'Adam'], 'color_value' => 123])
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            // Check if the key looks like a language code (e.g., 'en', 'ar', 'id')
            if (is_array($value) && (strlen($key) === 2 || strlen($key) === 5)) {
                foreach ($value as $translatedKey => $translatedValue) {
                    if ($this->isTranslationAttribute($translatedKey)) {
                        $this->setTranslationValue($translatedKey, $key, $translatedValue);
                    }
                }
                unset($attributes[$key]);
            }
        }

        return parent::fill($attributes);
    }

    /**
     * Public method to retrieve a field value for a specific language locale.
     */
    public function translate(string $attribute, ?string $locale = null): ?string
    {
        // 1. High-Performance Left-Join Optimization:
        // If the query used a leftJoin scope, the column exists directly on the primary attributes array.
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        $locale = $locale ?? $this->getLocale();

        // 2. Load translations from the database into the cache array if not already loaded
        if (! $this->translationsLoaded && $this->exists) {
            $this->loadTranslationsFromTable();
        }

        return $this->translationsCache[$locale][$attribute] ?? $this->getFallbackTranslation($attribute);
    }

    /**
     * Check if a specific string property name is declared as a translatable variable.
     */
    public function isTranslationAttribute(string $key): bool
    {
        return isset($this->translatable) && in_array($key, $this->translatable);
    }

    /**
     * Load all rows from the target database translation table into local memory cache.
     */
    public function loadTranslationsFromTable(): self
    {
        $records = DB::table($this->getTranslationTableName())
            ->where($this->getTranslationForeignKey(), $this->getKey())
            ->get();

        foreach ($records as $record) {
            foreach ((array) $record as $column => $value) {
                if (in_array($column, ['id', $this->getTranslationForeignKey(), 'locale'])) {
                    continue;
                }
                $this->translationsCache[$record->locale][$column] = $value;
            }
        }

        $this->translationsLoaded = true;

        return $this;
    }

    /**
     * Staged Memory Update: Write property variables straight into the memory array.
     */
    protected function setTranslationValue(string $attribute, string $locale, ?string $value): void
    {
        $this->translationsCache[$locale][$attribute] = $value;
    }

    /**
     * Database Mutation: Write cached memory arrays to the database table.
     */
    protected function saveTranslations(): void
    {
        foreach ($this->translationsCache as $locale => $attributes) {
            if (empty($attributes)) {
                continue;
            }

            DB::table($this->getTranslationTableName())->updateOrInsert(
                [
                    $this->getTranslationForeignKey() => $this->getKey(),
                    'locale' => $locale,
                ],
                $attributes
            );
        }
    }

    /**
     * Database Purge: Remove records from the database table.
     */
    protected function purgeTranslations(): void
    {
        DB::table($this->getTranslationTableName())
            ->where($this->getTranslationForeignKey(), $this->getKey())
            ->delete();
    }

    /**
     * Fallback Logic: Returns the first available translation if the requested language is missing.
     */
    protected function getFallbackTranslation(string $attribute): ?string
    {
        foreach ($this->translationsCache as $locale => $attributes) {
            if (! empty($attributes[$attribute])) {
                return $attributes[$attribute];
            }
        }

        return null;
    }

    protected function getLocale(): string
    {
        return App::getLocale();
    }

    protected function getTranslationTableName(): string
    {
        return Str::singular($this->getTable()).'_translations';
    }

    protected function getTranslationForeignKey(): string
    {
        return Str::singular($this->getTable()).'_id';
    }
}
