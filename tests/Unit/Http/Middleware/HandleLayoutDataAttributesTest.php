<?php

namespace Tests\Unit\Http\Middleware;

use App\Attributes\LayoutData;
use App\Http\Middleware\HandleLayoutDataAttributes;
use App\UI\Actions\ApplyLayoutMetadata;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Routing\Route;
use Illuminate\Routing\ViewController;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it calls apply layout metadata when attribute exists', function () {
    $applyLayout = Mockery::mock(ApplyLayoutMetadata::class);
    $mockResponse = Mockery::mock(ResponseFactory::class);
    $middleware = new HandleLayoutDataAttributes($applyLayout);

    $layoutData = new LayoutData(header: 'Test');
    // Create a mock that simulates ViewController behavior
    $controller = Mockery::mock();
    $controller->shouldReceive('callAction'); // Method needed by ViewController

    // Make it pass the ViewController check
    $controller->shouldReceive('__destruct'); // Just to make it mockable

    $route = Mockery::mock(Route::class);

    // Set up route call chain properly
    $route->shouldReceive('getController')
        ->andReturn($controller);

    $route->shouldReceive('getActionMethod')
        ->andReturn('index');

    $route->shouldReceive('parameters')
        ->andReturn(['layout_data' => $layoutData]);

    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn () => $route);

    $applyLayout->shouldReceive('execute')
        ->with($layoutData, ['layout_data' => $layoutData]);

    $next = fn ($req) => new Response;

    $response = $middleware->handle($request, $next);
    expect($response)->toBeInstanceOf(Response::class);
});
