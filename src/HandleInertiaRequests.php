<?php

namespace Inertia;

use Marko\Routing\Http\Request;
use Marko\Routing\Http\Response;
use Marko\Routing\Middleware\MiddlewareInterface;
use Inertia\Inertia;

/** @package Inertia */
class HandleInertiaRequests implements MiddlewareInterface
{
    protected Inertia $inertia;

    public function __construct(Inertia $inertia)
    {
        $this->inertia = $inertia;
    }

    public function handle(Request $request, callable $next): Response
    {
        $this->inertia->setRequest($request);

        $response = $next($request);
        
        $statusCode = $this->getStatusCode($request, $response);

         return new Response( 
            body: $response->body(),
            statusCode: $statusCode,
            headers: array_merge($response->headers(), ['Vary' => 'X-Inertia']),
        );
    }

    private function getStatusCode(Request $request, Response $response): int
    {
        if ($request->header('X-Inertia') && $response->statusCode() === 302) {
            if (in_array(strtoupper($request->method()), ['PUT', 'PATCH', 'DELETE'])) {
                return 303;
            }
        }
        return $response->statusCode();
    }
}