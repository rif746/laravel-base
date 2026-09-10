<?php

namespace App\Livewire\Concerns\Form;

use App\Attributes\Form\MapTranslation;
use App\Domains\System\Concerns\Model\HasTranslation;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Validate;
use Livewire\Form;
use ReflectionClass;
use ReflectionProperty;

/**
 * @mixin Form
 */
trait InteractWithTranslation
{
    protected static array $formTranslationMappingCache = [];

    /**
     * Fill translations with mapping
     *
     * @throws \Throwable
     */
    public function fillTranslation(Model $model): void
    {
        if (! method_exists($model, 'translate')) {
            throw new Exception(sprintf('Model [%s] not using trait %s', get_class($model), HasTranslation::class));
        }

        $this->mapTranslation();

        $formClass = static::class;
        $propertyMap = self::$formTranslationMappingCache[$formClass]['mapping'] ?? [];

        if (empty($propertyMap)) {
            return;
        }

        foreach ($propertyMap as $formProperty => $modelField) {
            if (! isset($this->{$formProperty}) || ! is_array($this->{$formProperty})) {
                $this->{$formProperty} = [];
            }

            foreach (self::getLocales() as $lc => $native) {
                $this->{$formProperty}[$lc] = $model->translate($modelField, $lc);
            }
            $this->validateOnly($formProperty);
        }

    }

    /**
     * Map validation rules, attributes, and model keys into the cache.
     */
    public function mapTranslation(): void
    {
        $formClass = static::class;

        if (isset(self::$formTranslationMappingCache[$formClass])) {
            return;
        }

        // Initialize cache keys for this specific form class
        self::$formTranslationMappingCache[$formClass] = [
            'rules' => [],
            'attributes' => [],
            'mapping' => [],
        ];

        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        // Prepare reflection properties ahead of time
        $asProperty = new ReflectionProperty(Validate::class, 'as');
        $rulesProperty = new ReflectionProperty(Validate::class, 'rule');

        foreach ($properties as $property) {
            $mapAttributes = $property->getAttributes(MapTranslation::class);
            if (empty($mapAttributes)) {
                continue;
            }

            /** @var MapTranslation $mapAttrInstance */
            $mapAttrInstance = $mapAttributes[0]->newInstance();
            self::$formTranslationMappingCache[$formClass]['mapping'][$property->name] = $mapAttrInstance->field;

            // Check if validation is defined on this localized property
            $validationAttributes = $property->getAttributes(Validate::class);
            if (! empty($validationAttributes)) {
                /** @var Validate $validateInstance */
                $validateInstance = $validationAttributes[0]->newInstance();

                $asAttr = $asProperty->getValue($validateInstance);
                $baseRules = $rulesProperty->getValue($validateInstance);

                // Safe conversion of rules to array format
                $baseRules = is_array($baseRules) ? $baseRules : explode('|', $baseRules);

                foreach (self::getLocales() as $lc => $native) {
                    $ruleKey = "{$property->name}.{$lc}";

                    self::$formTranslationMappingCache[$formClass]['rules'][$ruleKey] = $baseRules;
                    self::$formTranslationMappingCache[$formClass]['attributes'][$ruleKey] = __($asAttr)." ($native)";
                }
            }
        }
    }

    /**
     * Intercept Livewire Form validation rules calculation
     */
    public function getRules(): array
    {
        $this->mapTranslation();

        $formClass = static::class;
        $standardRules = parent::getRules();
        $dynamicRules = self::$formTranslationMappingCache[$formClass]['rules'] ?? [];

        // Remove root localized array keys if they were registered by default parent hooks
        foreach (array_keys(self::$formTranslationMappingCache[$formClass]['mapping'] ?? []) as $rootKey) {
            unset($standardRules[$rootKey]);
        }

        return array_merge($standardRules, $dynamicRules);
    }

    /**
     * Intercept Livewire Form validation attributes calculation
     */
    public function getValidationAttributes(): array
    {
        $this->mapTranslation();

        $formClass = static::class;
        $standardAttributes = parent::getValidationAttributes();
        $dynamicAttributes = self::$formTranslationMappingCache[$formClass]['attributes'] ?? [];

        foreach (array_keys(self::$formTranslationMappingCache[$formClass]['mapping'] ?? []) as $rootKey) {
            unset($standardAttributes[$rootKey]);
        }

        return array_merge($standardAttributes, $dynamicAttributes);
    }

    /**
     * Fetch standard dictionary format: ['en' => 'English', 'ar' => 'Arabic']
     */
    private static function getLocales(): array
    {
        $locales = config('locales.support') ?? [];
        $mapped = [];

        foreach ($locales as $key => $support) {
            $mapped[$key] = $support['native'] ?? $key;
        }

        return $mapped;
    }
}
