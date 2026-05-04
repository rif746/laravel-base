<?php

namespace App\Http\Middleware;

use App\Attributes\Seo;
use Artesaos\SEOTools\Facades\SEOTools;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleSeoAttribute
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Get the view data (variables passed to the view)
        if ($response instanceof \Illuminate\Http\Response && method_exists($response, 'getOriginalContent')) {
            $content = $response->getOriginalContent();

            $viewData = $content instanceof View
                ? $content->getData()
                : [];

            $seo = $this->getSeoAttribute($request);

            if ($seo) {
                $this->applySeo($seo, $viewData);
            }
        }

        return $next($request);
    }

    protected function applySeo(Seo $seo, array $viewData): void
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

        if ($keywords) {
            SEOTools::metatags()->setKeywords($keywords);
        }
    }

    private function translateValue(string|array|null $key, array $replace = []): string|array|null
    {
        if (! $key) {
            return null;
        }

        // Check if the string looks like a translation key (contains a dot)
        // and if that key actually exists in our lang files.
        if (is_string($key) && str_contains($key, '.') && \Lang::has($key)) {
            return __($key, $replace);
        }

        return $key; // Return as plain text if not a translation key
    }

    private function resolveDynamicValue($value, $data)
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

    /**
     * Extract the #[Seo] attribute from the current controller method.
     */
    private function getSeoAttribute($request): ?Seo
    {
        $route = $request->route();

        // Check if the route is associated with a controller action
        if ($route && $route->getController() && $route->getActionMethod()) {
            try {
                $reflection = new \ReflectionMethod(
                    $route->getController(),
                    $route->getActionMethod()
                );

                $attributes = $reflection->getAttributes(Seo::class);

                if (! empty($attributes)) {
                    // Instantiate and return the Seo attribute class
                    return $attributes[0]->newInstance();
                }
            } catch (\ReflectionException $e) {
                // Log error or ignore if the method doesn't exist
                return null;
            }
        }

        return null;
    }
}
