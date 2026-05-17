<?php

namespace App\Http\Middleware;

use App\Actions\System\SetSeoMetadata;
use App\Attributes\Seo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\ViewController;
use Symfony\Component\HttpFoundation\Response;

class HandleSeoAttribute
{
    public function __construct(
        protected SetSeoMetadata $setSeo
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route) {
            $seoInstance = $this->getSeoAttributeDirectly($route);

            if ($seoInstance) {
                $this->setSeo->applySeo($seoInstance, $request->all());
            }
        }

        return $next($request);
    }

    /**
     * Extracts the attribute directly from the route structure safely.
     */
    private function getSeoAttributeDirectly($route): ?Seo
    {
        if (isset($route->defaults['seo']) && $route->defaults['seo'] instanceof Seo) {
            return $route->defaults['seo'];
        }

        try {
            $controller = $route->getController();

            if ($controller instanceof ViewController) {
                return isset($route->defaults['seo']) && $route->defaults['seo'] instanceof Seo
                    ? $route->defaults['seo']
                    : null;
            }

            $method = $route->getActionMethod();

            if ($method === get_class($controller) || (method_exists($controller, '__invoke') && ! method_exists($controller, $method))) {
                $method = '__invoke';
            }

            if (method_exists($controller, $method)) {
                $reflection = new \ReflectionMethod($controller, $method);
                $attributes = $reflection->getAttributes(Seo::class);

                if (! empty($attributes)) {
                    return $attributes[0]->newInstance();
                }
            }
        } catch (\ReflectionException $e) {
            return null;
        }

        return null;
    }
}
