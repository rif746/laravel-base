<?php

namespace App\Http\Middleware;

use App\Attributes\LayoutData;
use App\UI\Actions\ApplyLayoutMetadata;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\ViewController;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use ReflectionMethod;

class HandleLayoutDataAttributes
{
    public function __construct(
        protected ApplyLayoutMetadata $applyLayout
    ) {}

    /**
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route) {
            $layoutInstance = $this->getLayoutAttributeDirectly($route);

            if ($layoutInstance) {
                // 💡 CRITICAL: We pass route parameters (the bound Eloquent models)
                // so the named-route parameter builder can extract IDs (e.g., {user.id})
                $this->applyLayout->execute($layoutInstance, $route->parameters());
            }
        }

        return $next($request);
    }

    /**
     * Extracts the LayoutData attribute directly from the route structure safely.
     * @throws Exception
     */
    private function getLayoutAttributeDirectly(mixed $route): ?LayoutData
    {
        if (isset($route->defaults['layout_data']) && $route->defaults['layout_data'] instanceof LayoutData) {
            return $route->defaults['layout_data'];
        }

        try {
            $controller = $route->getController();

            if ($controller instanceof ViewController) {
                return isset($route->defaults['layout_data']) && $route->defaults['layout_data'] instanceof LayoutData
                    ? $route->defaults['layout_data']
                    : null;
            }

            $method = $route->getActionMethod();

            if ($method === get_class($controller) || (method_exists($controller, '__invoke') && ! method_exists($controller, $method))) {
                $method = '__invoke';
            }

            if (method_exists($controller, $method)) {
                $reflection = new ReflectionMethod($controller, $method);
                $attributes = $reflection->getAttributes(LayoutData::class);

                if (! empty($attributes)) {
                    $layoutInstance = $attributes[0]->newInstance();
                    if (! $layoutInstance instanceof LayoutData) {
                        throw new Exception('Expected type of LayoutData object');
                    }
                    return $layoutInstance;
                }
            }
        } catch (ReflectionException $e) {
            return null;
        }

        return null;
    }
}
