<?php

declare(strict_types=1);

namespace Inertia\Middleware;

use Inertia\InertiaInterface;
use Marko\Routing\Http\Request;
use Marko\Routing\Http\Response;
use Marko\Routing\Middleware\MiddlewareInterface;

class HandleInertiaRequests implements MiddlewareInterface
{
    protected InertiaInterface $inertia;

    public function __construct(InertiaInterface $inertia)
    {
        $this->inertia = $inertia;
    }

    public function version(): ?string
    {
        return null;
    }

    /** @return array<string, mixed> */
    public function share(Request $request): array
    {
        return [];
    }

    public function rootView(): string
    {
        return 'inertia-marko::app';
    }

    public function handle(Request $request, callable $next): Response
    {
        if ($version = $this->version()) {
            $this->inertia->setVersion($version);
        }

        foreach ($this->share($request) as $key => $value) {
            $this->inertia->share($key, $value);
        }

        $this->inertia->setRootView($this->rootView());
        $this->inertia->setRequest($request);

        $response = $next($request);

        if (
            $request->header('X-Inertia') === 'true' &&
            $request->method() === 'GET' &&
            $this->inertia->getVersion() !== null
        ) {
            $clientVersion = $request->header('X-Inertia-Version');
            if ($clientVersion !== null && $clientVersion !== $this->inertia->getVersion()) {
                return new Response(
                    body: '',
                    statusCode: 409,
                    headers: ['X-Inertia-Location' => $request->path()],
                );
            }
        }

        $statusCode = $this->getStatusCode($request, $response);

        return new Response(
            body: $response->body(),
            statusCode: $statusCode,
            headers: array_merge($response->headers(), ['Vary' => 'X-Inertia']),
        );
    }

    private function getStatusCode(Request $request, Response $response): int
    {
        if ($request->header('X-Inertia') === 'true' && $response->statusCode() === 302) {
            if (in_array(strtoupper($request->method()), ['PUT', 'PATCH', 'DELETE'])) {
                return 303;
            }
        }

        return $response->statusCode();
    }
}
