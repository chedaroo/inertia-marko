<?php

declare(strict_types=1);

use Inertia\Inertia;
use Marko\Routing\Http\Request;

it('throws when no request has been set', function () {
    $inertia = new Inertia(fakeView());
    $inertia->render('Dashboard');
})->throws(RuntimeException::class);

it('returns a json when the request has the X-Inertia header', function () {
    $request = new Request([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '/test',
        'HTTP_X_INERTIA' => 'true',
    ]);

    $inertia = new Inertia(fakeView());
    $inertia->setRequest($request);

    $response = $inertia->render('Test', ['foo' => 'bar']);

    expect($response->headers()['Content-Type'])->toContain('application/json');
    expect(json_decode($response->body(), true))->toMatchArray([
        'component' => 'Test',
        'props' => ['foo' => 'bar'],
        'url' => '/test',
        'version' => '',
    ]);
});

it('returns html when the request does not have the X-Inertia header', function () {
    $request = new Request([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '/test',
    ]);

    $inertia = new Inertia(fakeView());
    $inertia->setRequest($request);

    $response = $inertia->render('Test', ['foo' => 'bar']);

    expect($response->headers()['Content-Type'])->toContain('text/html');
    expect($response->body())->toContain('<h1>Fake View</h1>');
});

it('merges shared props with render props', function () {
    $request = new Request([
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '/test',
        'HTTP_X_INERTIA' => 'true',
    ]);

    $inertia = new Inertia(fakeView());
    $inertia->setRequest($request);
    $inertia->share('shared', 'shared value');

    $response = $inertia->render('Test', ['foo' => 'bar']);

    expect(json_decode($response->body(), true))->toMatchArray([
        'component' => 'Test',
        'props' => [
            'shared' => 'shared value',
            'foo' => 'bar',
        ],
        'url' => '/test',
        'version' => '',
    ]);
});
