<?php

namespace App\UI\Actions;

use App\Attributes\Seo;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SetSeoMetadata
{
    public function applySeo(Seo $seo, array $viewData, Request $request): void
    {
        // 1. Resolve Dynamic Data (e.g., 'user.name')
        $name = $this->resolveDynamicValue($seo->name, $viewData);

        // 2. Resolve Translation with optional placeholders
        $title = $this->translateValue($seo->title, ['name' => $name]);
        $description = $this->translateValue($seo->description);
        $keywords = $this->translateValue($seo->keywords);

        // 3. Fallback to Defaults
        SEOTools::setTitle($title ?? __('domains/system.seo.default_title'));
        SEOTools::setDescription($description ?? __('domains/system.seo.default_description'));
        SEOTools::metatags()->setCanonical($request->url());

        if ($keywords) {
            SEOTools::metatags()->setKeywords($keywords);
            SEOTools::opengraph()->addProperty('keyword', $keywords);
            SEOTools::twitter()->addValue('keyword', $keywords);
            SEOTools::jsonLd()->addValue('keyword', $keywords);
        }

        if (! empty($seo->image)) {
            SEOTools::addImages($seo->image);
            SEOTools::opengraph()->addProperty('image', asset($seo->image));
            SEOTools::twitter()->addValue('image', asset($seo->image));
            SEOTools::jsonLd()->addValue('image', asset($seo->image));
        }
    }

    private function translateValue(string|array|null $key, array $replace = []): string|array|null
    {
        if (! $key) {
            return null;
        }

        // Check if the string looks like a translation key (contains a dot)
        // and if that key actually exists in our lang files.
        if (is_string($key) && str_contains($key, '.') && Lang::has($key)) {
            return __($key, $replace);
        }

        return $key; // Return as plain text if not a translation key
    }

    private function resolveDynamicValue(string $value, array $data)
    {
        // If the value contains a dot (e.g., 'post.title')
        if (str_contains($value, '.')) {
            [$objectName, $property] = explode('.', $value);

            // Check if 'post' exists in the view data
            if (isset($data[$objectName])) {
                return $data[$objectName]->{$property};
            }
        }

        return $value; // Return static string if no placeholder found
    }
}
