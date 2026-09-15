<?php

namespace App\Http\Concerns;

use Illuminate\Http\Request;

/**
 * @mixin \Illuminate\Http\Resources\Json\JsonResource
 */
trait ResolveTranslations
{
    /**
     * Resolve localized translation string based on request or active application locale.
     */
    protected function resolveTranslation(string $attribute, ?string $locale = null): ?string
    {
        // Safety check to ensure $this->resource exists
        if (! isset($this->resource)) {
            return null;
        }

        // Force the underlying model to return raw translations dictionary if property exists
        if (property_exists($this->resource, 'returnRawTranslations')) {
            $this->resource->returnRawTranslations = true;
        }

        $rawTranslations = $this->resource->{$attribute} ?? null;

        if (is_array($rawTranslations)) {
            $currentLocale = $locale ?? app()->getLocale();
            $fallbackLocale = config('app.fallback_locale', 'en');

            return $rawTranslations[$currentLocale]
                ?? $rawTranslations[$fallbackLocale]
                ?? reset($rawTranslations)
                ?? null;
        }

        return is_string($rawTranslations) ? $rawTranslations : null;
    }

    /**
     * Resolve a raw dictionary of all available translations for specific attributes.
     *
     * @param array<int, string> $attributes
     * @return array<string, mixed>
     */
    protected function resolveRawTranslations(array $attributes): array
    {
        $this->resource->returnRawTranslations = true;
        $dictionary = [];

        foreach ($attributes as $attribute) {
            $dictionary[$attribute] = $this->resource->{$attribute} ?? [];
        }

        return $dictionary;
    }

    /**
     * Conditionally include all raw translation dictionaries when the 'include_translations' query parameter is present.
     *
     * @param array<int, string> $attributes
     */
    protected function whenTranslationsRequested(Request $request, array $attributes): mixed
    {
        return $this->when(
            $request->boolean('include_translations'),
            fn () => $this->resolveRawTranslations($attributes)
        );
    }
}
