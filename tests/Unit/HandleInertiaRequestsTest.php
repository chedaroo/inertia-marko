<?php

declare(strict_types=1);

use Inertia\Inertia;
use Inertia\Middleware\HandleInertiaRequests;
use Marko\Routing\Http\Request;
use Marko\Routing\Http\Response;

it('redirect 302 to 303 for mutating methods', function (string $method) {
    $inertia = new Inertia(fakeView());
    $middleware = new HandleInertiaRequests($inertia);

    $request = new Request([
        'REQUEST_METHOD' => $method,
        'REQUEST_URI' => '/test',
        'HTTP_X_INERTIA' => 'true',
    ]);

    $response = $middleware->handle($request, $next = function () {
        return new Response(statusCode: 302);
    });

    expect($response->statusCode())->toBe(303);
})->with(['PUT', 'PATCH', 'DELETE']);

it('does not redirect 302 to 303 for GET requests', function () {
    $inertia = new Inertia(fakeView());
    $middleware = new HandleInertiaRequests($inertia);

    $request = new Request([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '/test',
        'HTTP_X_INERTIA' => 'true',
    ]);

    $response = $middleware->handle($request, $next = function () {
        return new Response(statusCode: 302);
    });

    expect($response->statusCode())->toBe(302);
});

it('adds the Vary header to the response', function () {
    $inertia = new Inertia(fakeView());
    $middleware = new HandleInertiaRequests($inertia);

    $request = new Request([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '/test',
        'HTTP_X_INERTIA' => 'true',
    ]);

    $response = $middleware->handle($request, $next = function () {
        return new Response(statusCode: 200);
    });

    expect($response->headers()['Vary'])->toBe('X-Inertia');
});

it('has a version mismatch', function () {
    $inertia = new Inertia(fakeView());
    $inertia->setVersion('1');

    $middleware = new HandleInertiaRequests($inertia);

    $request = new Request([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '/test',
        'HTTP_X_INERTIA' => 'true',
        'HTTP_X_INERTIA_VERSION' => '2',
    ]);

    $response = $middleware->handle($request, $next = function () {
        return new Response(statusCode: 200);
    });

    expect($response->statusCode())->toBe(409);
    expect($response->headers()['X-Inertia-Location'])->toBe('/test');
});
