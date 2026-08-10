<?php

namespace App\Livewire\Concerns\Form;

use App\Attributes\Form\MapNested;
use App\Attributes\Form\MapTo;
use App\Attributes\Form\MapTranslation;
use Exception;
use Livewire\Form;
use ReflectionClass;

/**
 * @mixin Form
 */
trait InteractWithDto
{
    protected static array $dtoCache = [];

    /**
     * Converts form properties into its configured DTO tree.
     *
     * @throws Exception
     */
    public function toDto(string $context = 'default', array $extraData = []): mixed
    {
        $cacheKey = static::class.':'.$context;

        // 1. Build cache blueprint ONCE
        if (! isset(self::$dtoCache[$cacheKey])) {
            $reflection = new ReflectionClass($this);
            $mapped = [];
            $targetClass = null;

            foreach ($reflection->getProperties() as $prop) {
                // Check for standard mapping
                $mapToAttrs = $prop->getAttributes(MapTo::class);
                $mapNestedAttrs = $prop->getAttributes(MapNested::class);

                if (empty($mapToAttrs) && empty($mapNestedAttrs)) {
                    continue;
                }

                // A) Handle Standard #[MapTo]
                foreach ($mapToAttrs as $attr) {
                    /** @var MapTo $map */
                    $map = $attr->newInstance();

                    $hasContextMatch = is_array($map->context)
                        ? in_array($context, $map->context, true)
                        : $context === $map->context;

                    if (! $hasContextMatch) {
                        continue;
                    }

                    $field = $map->field ?? $prop->name;
                    $isTranslated = ! empty($prop->getAttributes(MapTranslation::class));

                    if (! $isTranslated) {
                        $targetClass = $map->dtoClass;
                    }

                    $mapped[$field] = [
                        'prop' => $prop->name,
                        'type' => $isTranslated ? 'translation' : 'flat',
                        'sub_dto' => $map->dtoClass,
                    ];
                }

                // B) Handle Non-Translation Nested DTOs via #[MapNested]
                foreach ($mapNestedAttrs as $attr) {
                    /** @var MapNested $nested */
                    $nested = $attr->newInstance();
                    $field = $prop->name;

                    $mapped[$field] = [
                        'prop' => $prop->name,
                        'type' => $nested->isArray ? 'nested_array' : 'nested_object',
                        'sub_dto' => $nested->dtoClass,
                    ];
                }
            }

            if (! $targetClass) {
                throw new Exception(
                    sprintf('No valid root MapTo configuration found for context [%s] in [%s].', $context, static::class)
                );
            }

            // Find where translations belong
            $transParamName = 'translations';
            $constructor = (new ReflectionClass($targetClass))->getConstructor();
            if ($constructor) {
                foreach ($constructor->getParameters() as $param) {
                    if ($param->getName() === 'translations' || $param->getType()?->getName() === 'array') {
                        $transParamName = $param->getName();
                        break;
                    }
                }
            }

            self::$dtoCache[$cacheKey] = [
                'class' => $targetClass,
                'trans_param' => $transParamName,
                'mapped' => $mapped,
            ];
        }

        // 2. Map runtime values
        $config = self::$dtoCache[$cacheKey];
        $dtoClass = $config['class'];
        $arguments = $extraData;
        $translations = [];

        foreach ($config['mapped'] as $field => $info) {
            $value = $this->{$info['prop']} ?? null;

            switch ($info['type']) {
                case 'flat':
                    $arguments[$field] = $value;
                    break;

                case 'translation':
                    $subDto = $info['sub_dto'];
                    $value = is_array($value) ? $value : [];
                    foreach ($value as $locale => $text) {
                        $translations[$subDto][$locale][$field] = $text;
                    }
                    break;

                case 'nested_object':
                    // e.g. $this->address array -> new AddressDTO(...$value)
                    if (is_array($value)) {
                        $subDto = $info['sub_dto'];
                        $arguments[$field] = new $subDto(...$value);
                    } else {
                        $arguments[$field] = $value; // If it's already an object or null
                    }
                    break;

                case 'nested_array':
                    // e.g. $this->items array -> [new ItemDTO(...$item1), new ItemDTO(...$item2)]
                    if (is_array($value)) {
                        $subDto = $info['sub_dto'];
                        $arguments[$field] = array_map(
                            fn ($item) => is_array($item) ? new $subDto(...$item) : $item,
                            $value
                        );
                    } else {
                        $arguments[$field] = [];
                    }
                    break;
            }
        }

        // 3. Process localized translations
        if (! empty($translations)) {
            $transParam = $config['trans_param'];
            $arguments[$transParam] = [];

            foreach ($translations as $class => $localeGroups) {
                foreach ($localeGroups as $lc => $dtoData) {
                    $dtoData['locale'] = $lc;
                    $arguments[$transParam][] = new $class(...$dtoData);
                }
            }
        }

        return new $dtoClass(...$arguments);
    }

    public static function resetDtoCache(): void
    {
        self::$dtoCache = [];
    }
}
