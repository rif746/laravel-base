<?php

namespace App\Domains\System\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AsJsonTranslation implements CastsAttributes
{
    /**
     * Cast a raw database JSON string into an active localized string.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        $decoded = is_array($value) ? $value : json_decode((string) $value, true);

        if (! is_array($decoded)) {
            return $value;
        }

        // Return raw translation dictionary if requested by CMS or admin forms
        if (property_exists($model, 'returnRawTranslations') && $model->returnRawTranslations) {
            return $decoded;
        }

        $currentLocale = App::getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        return $decoded[$currentLocale]
            ?? $decoded[$fallbackLocale]
            ?? reset($decoded)
            ?? null;
    }

    /**
     * Prepare given localized value for database JSON storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        // Handle single string assignment: $model->title = 'Sample' (Saves to active locale)
        if (is_string($value)) {
            $existing = isset($attributes[$key]) ? json_decode((string) $attributes[$key], true) : [];
            $existing = is_array($existing) ? $existing : [];
            $existing[App::getLocale()] = $value;

            return json_encode($existing, JSON_UNESCAPED_UNICODE);
        }

        // Handle full locale dictionary payload: ['id' => 'Judul', 'en' => 'Title']
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $value;
    }
}
