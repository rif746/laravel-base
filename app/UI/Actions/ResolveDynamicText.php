<?php

namespace App\UI\Actions;

use Illuminate\Support\Facades\Lang;
use Throwable;

class ResolveDynamicText
{
    /**
     * Cache store indexed by object hash and string path to prevent duplicate evaluation
     * Structure: [context_hash => [path_string => resolved_scalar_string]]
     */
    private array $resolvedPathCache = [];

    public function execute(?string $value, array|object $context, ?string $contextTarget = null): ?string
    {
        if (! $value) {
            return null;
        }

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
                $bindings[$variableName] = $this->resolveNestedValue($data, $variableName, $contextTarget);
            }
        }

        return __($key, $bindings);
    }

    private function resolveExplicitPlaceholders(string $text, array|object $data, ?string $contextTarget): string
    {
        preg_match_all('/\{([^}]+)}/', $text, $matches);
        if (empty($matches[1])) {
            return $text;
        }

        foreach ($matches[1] as $placeholder) {
            $replacementValue = $this->resolveNestedValue($data, $placeholder, $contextTarget);
            $text = str_replace("{{$placeholder}}", $replacementValue, $text);
        }

        return $text;
    }

    private function resolveNestedValue(array|object $data, string $path, ?string $contextTarget = null): string
    {
        // 1. Generate a stable cache key representing the current base context state
        $contextKey = is_object($data) ? spl_object_hash($data) : md5(serialize($data));
        $cachePathKey = "{$contextTarget}.{$path}";

        // 2. Return early if this exact path combination has already been evaluated on this request
        if (isset($this->resolvedPathCache[$contextKey][$cachePathKey])) {
            return $this->resolvedPathCache[$contextKey][$cachePathKey];
        }

        try {
            $resolved = null;

            // SCENARIO A: Look for the context wrapper object first (e.g. 'admissionSchedule')
            // Using data_get directly on the base container honors custom dynamic getters/methods natively
            $targetObject = ! empty($contextTarget) ? data_get($data, $contextTarget) : null;

            if ($targetObject !== null) {
                // Read the relation path directly from the live object instance (triggers magic getters/relations)
                $resolved = data_get($targetObject, $path);
            }

            // SCENARIO B: Fallback to evaluating from the root container context directly
            if ($resolved === null || $resolved === '') {
                $resolved = data_get($data, $path);
            }

            // Normalize final output value to a string format
            $output = is_scalar($resolved) ? (string) $resolved : '';

            // Cache the final resolved string output
            $this->resolvedPathCache[$contextKey][$cachePathKey] = $output;

            return $output;

        } catch (Throwable $e) {
            // Graceful fallback if anything unexpected misfires during dynamic resolution
            return '';
        }
    }
}
