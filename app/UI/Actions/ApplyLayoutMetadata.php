<?php

namespace App\UI\Actions;

use App\Attributes\LayoutData;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class ApplyLayoutMetadata
{
    public function __construct(
        protected ResolveDynamicText $textResolver
    ) {}

    public function execute(LayoutData $attributeInstance, array|object $dataContext): void
    {
        $contextTarget = $attributeInstance->context;
        $breadcrumbs = [];

        foreach ($attributeInstance->breadcrumbs as $labelKey => $routeConfig) {
            // 1. Resolve the text label (Supports translation keys or direct placeholders)
            $label = $this->textResolver->execute($labelKey, $dataContext, $contextTarget);
            $url = null;

            if (! empty($routeConfig)) {
                $routeName = '';
                $routeParams = [];

                // SCENARIO A: Route configuration has parameters array: ["route.name", ["id" => "{user.id}"]]
                if (is_array($routeConfig)) {
                    $routeName = $routeConfig[0] ?? '';
                    $rawParams = $routeConfig[1] ?? [];

                    foreach ($rawParams as $paramKey => $paramValue) {
                        // Resolve internal variable bindings within parameters (e.g., "{user.id}" -> 4)
                        $resolvedValue = $this->textResolver->execute($paramValue, $dataContext, $contextTarget);
                        $routeParams[$paramKey] = $resolvedValue;
                    }
                }
                // SCENARIO B: Route configuration is a simple flat string: "identity.dashboard"
                elseif (is_string($routeConfig)) {
                    $routeName = $routeConfig;
                }

                // 2. Compile URL safely via route name if it exists in Laravel's routing registry
                if (! empty($routeName) && Route::has($routeName)) {
                    $url = route($routeName, $routeParams);
                } else {
                    // Fallback to treat as a raw URI link path if it's not a registered route name
                    $url = ! empty($routeName) ? url($routeName) : null;
                }
            }

            $breadcrumbs[] = [
                'label' => $label,
                'url' => $url,
            ];
        }

        // 3. Resolve layout headline text patterns
        $header = null;
        if ($attributeInstance->header) {
            $header = $this->textResolver->execute($attributeInstance->header, $dataContext, $contextTarget);
        }

        if (empty($header) && ! empty($breadcrumbs)) {
            $lastCrumb = end($breadcrumbs);
            $header = $lastCrumb['label'] ?? '';
        }

        // 4. Inject properties into the layout view instance memory
        View::composer([
            'components.layouts.app',
            'components.layouts.guest',
        ], fn ($view) => $view->with([
            'breadcrumbs' => $breadcrumbs,
            'header' => $header ?? config('app.name', 'Antigravity App'),
        ]));
    }
}
