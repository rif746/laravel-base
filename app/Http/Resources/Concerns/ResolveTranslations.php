<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        $locale = $locale ?? App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        // Force the underlying model to return the raw translation dictionary if enabled
        $this->resource->returnRawTranslations = true;
        $rawTranslations = $this->resource->{$attribute};

        if (is_array($rawTranslations)) {
            return $rawTranslations[$locale]
                ?? $rawTranslations[$fallbackLocale]
                ?? reset($rawTranslations)
                ?? null;
        }

        return is_string($rawTranslations) ? $rawTranslations : null;
    }

    /**
     * Resolve raw dictionary of all available translations for specific attributes.
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
     * Conditionally include all raw translation dictionaries when 'include_translations' query parameter is present.
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
