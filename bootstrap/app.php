<?php

use App\Http\Middleware\HandleLayoutDataAttributes;
use App\Http\Middleware\HandlePreferredLanguage;
use App\Http\Middleware\HandlePreferredTimezone;
use App\Http\Middleware\HandleSeoAttributes;
use App\Http\Middleware\HandleSeoSetting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seo' => HandleSeoAttributes::class,
            'layouts' => HandleLayoutDataAttributes::class,
        ]);
        $middleware->web(append: [
            HandleSeoSetting::class,
            HandlePreferredTimezone::class,
            HandlePreferredLanguage::class,
        ]);
        $middleware->statefulApi();
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Intercept all API exception renders centrally
        $exceptions->render(function (Throwable $e, $request) {
            // Apply only for API routes or JSON-accepting clients
            if (! $request->is('api/*') && ! $request->wantsJson()) {
                return null;
            }

            // 1. Validation Errors (HTTP 422)
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => __('ui/crud.error.validation_failed'),
                    'errors' => $e->validator->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // 2. Authentication Errors (HTTP 401)
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => __('auth.unauthenticated'),
                    'errors' => (object) [],
                ], Response::HTTP_UNAUTHORIZED);
            }

            // 3. Authorization / Policy Errors (HTTP 403)
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'message' => $e->getMessage() ?: __('ui/crud.error.forbidden'),
                    'errors' => (object) [],
                ], Response::HTTP_FORBIDDEN);
            }

            // 4. Model / Resource Not Found (HTTP 404)
            if ($e instanceof ModelNotFoundException) {
                $modelName = class_basename($e->getModel());
                return response()->json([
                    'message' => "{$modelName} record not found.",
                    'errors' => (object) [],
                ], Response::HTTP_NOT_FOUND);
            }

            // 5. Standard HTTP Exceptions (e.g. 404 Route Not Found, 405 Method Not Allowed)
            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'message' => $e->getMessage() ?: Response::$statusTexts[$e->getStatusCode()] ?? 'HTTP Error',
                    'errors' => (object) [],
                ], $e->getStatusCode());
            }

            // 6. Generic Server Error Fallback (HTTP 500)
            $isLocal = config('app.env') === 'local' || config('app.debug') === true;

            return response()->json([
                'message' => $isLocal ? $e->getMessage() : __('ui/crud.error.generic'),
                'errors' => $isLocal ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : (object) [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
