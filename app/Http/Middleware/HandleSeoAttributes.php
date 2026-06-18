<?php

namespace App\Http\Middleware;

use App\Attributes\Seo;
use App\UI\Actions\SetSeoMetadata;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\ViewController;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;

class HandleSeoAttributes
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
                $this->setSeo->applySeo($seoInstance, $request->all(), $request);
            }
        }

        return $next($request);
    }

    /**
     * Extracts the attribute directly from the route structure safely.
     *
     * @throws Exception
     */
    private function getSeoAttributeDirectly(mixed $route): ?Seo
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
                $reflection = new ReflectionMethod($controller, $method);
                $attributes = $reflection->getAttributes(Seo::class);

                if (! empty($attributes)) {
                    $seoInstance = $attributes[0]->newInstance();
                    if (! $seoInstance instanceof Seo) {
                        throw new Exception('Expected type of Seo object');
                    }

                    return $seoInstance;
                }
            }
        } catch (ReflectionException $e) {
            return null;
        }

        return null;
    }
}
