<?php
namespace App\UI\Actions;

use Illuminate\Support\Facades\Lang;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use Throwable;

class ResolveDynamicText
{
    /**
     * Cache store to prevent duplicate reflection lookups and redundant query executions
     * Structure: [object_hash_or_array_id => [context_key => resolved_object_or_scalar]]
     */
    private array $resolvedContextCache = [];

    public function execute(?string $value, array|object $context, ?string $contextTarget = null): ?string
    {
        if (! $value) return null;

        if (str_contains($value, '.') && Lang::has($value)) {
            return $this->localizeAndBind($value, $context, $contextTarget);
        }

        return $this->resolveExplicitPlaceholders($value, $context, $contextTarget);
    }

    private function localizeAndBind(string $key, array|object $data, ?string $contextTarget): string
    {
        $templateString = __($key);
        preg_match_all('/:([a-zA-Z0-9_]+)/', $templateString, $matches);

        $bindings = [];
        if (! empty($matches[1])) {
            foreach ($matches[1] as $variableName) {
                $targetProperty = $variableName;
                $objectKey = $contextTarget;

                $bindings[$variableName] = $this->resolveNestedValue(
                    $objectKey ?? $variableName,
                    $objectKey ? $targetProperty : null,
                    $data
                );
            }
        }

        return __($key, $bindings);
    }

    private function resolveExplicitPlaceholders(string $text, array|object $data, ?string $contextTarget): string
    {
        preg_match_all('/\{([^}]+)\}/', $text, $matches);
        if (empty($matches[1])) return $text;

        foreach ($matches[1] as $placeholder) {
            if (str_contains($placeholder, '.')) {
                [$objectName, $property] = explode('.', $placeholder, 2);
            } else {
                $objectName = $contextTarget ?? $placeholder;
                $property = $contextTarget ? $placeholder : null;
            }

            $replacementValue = $this->resolveNestedValue($objectName, $property, $data);
            $text = str_replace("{{$placeholder}}", $replacementValue, $text);
        }

        return $text;
    }

    private function resolveNestedValue(string $objectName, ?string $property, array|object $data): string
    {
        // 1. Generate a unique cache signature for the current payload state
        $contextKey = is_object($data) ? spl_object_hash($data) : md5(serialize($data));

        // 2. Warm up the context cache exactly once per component/request
        if (! isset($this->resolvedContextCache[$contextKey])) {
            $this->resolvedContextCache[$contextKey] = is_object($data)
                ? $this->extractInspectableObjects($data)
                : $data;
        }

        $cachedContext = $this->resolvedContextCache[$contextKey];
        $target = null;

        // 3. Look up the cached target context safely without firing new database calls
        if (isset($cachedContext[$objectName])) {
            $target = $cachedContext[$objectName];
        } elseif ($property === null && isset($cachedContext[$objectName]) && is_scalar($cachedContext[$objectName])) {
            return (string) $cachedContext[$objectName];
        }

        if ($property === null) {
            return $target && is_scalar($target) ? (string) $target : '';
        }

        if ($target && is_object($target) && isset($target->{$property})) return (string) $target->{$property};
        if ($target && is_array($target) && isset($target[$property])) return (string) $target[$property];

        return '';
    }

    /**
     * Reflects and extracts object contexts ONCE, caching the output in memory.
     */
    private function extractInspectableObjects(object $component): array
    {
        $objects = [];
        $reflection = new ReflectionClass($component);

        // Scan Public Properties
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
            $value = $prop->getValue($component);
            $objects[$prop->getName()] = $value;
        }

        // Scan Public Methods (e.g. Livewire 4 #[Computed] properties)
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getNumberOfParameters() === 0 && !$method->isStatic()) {
                try {
                    $name = $method->getName();
                    // Ignore internal framework overrides and lifecycle names
                    if (! str_starts_with($name, '__') && ! str_starts_with($name, 'get') && ! str_contains($name, 'rendering')) {

                        // 💡 THIS WAS THE CULPRIT: Invoking this on every string match triggered query loops.
                        // Now it runs exactly once per request render pass, and the model is stored in memory.
                        $objects[$name] = $component->{$name};
                    }
                } catch (Throwable $e) {
                    continue;
                }
            }
        }

        return $objects;
    }
}
