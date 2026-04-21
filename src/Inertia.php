<?php

declare(strict_types=1);

namespace Inertia;

use Marko\Routing\Http\Request;
use Marko\Routing\Http\Response;
use Marko\View\ViewInterface;
use RuntimeException;

class Inertia implements InertiaInterface
{
    private ?Request $request = null;

    private string $rootView = 'inertia-marko::app';

    /** @var array<string, mixed> */
    private array $sharedProps = [];

    private ?string $version = null;

    public function __construct(
        private readonly ViewInterface $view,
    ) {}

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setRootView(string $rootView): void
    {
        $this->rootView = $rootView;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function share(string $key, mixed $value): void
    {
        $this->sharedProps[$key] = $value;
    }

    /** @param array<string, mixed> $props */
    public function render(string $component, array $props = []): Response
    {

        if ($this->request === null) {
            throw new RuntimeException(
                'No request set on Inertia service. Did you add the HandleInertiaRequests middleware?',
            );
        }

        $props = array_merge($this->sharedProps, $props);

        $data = [
            'component' => $component,
            'props' => $props,
            'url' => $this->request->path(),
            'version' => $this->version ?? '',
        ];

        if ($this->request->header('X-Inertia') === 'true') {

            return new Response(
                body: json_encode($data, JSON_THROW_ON_ERROR),
                statusCode: 200,
                headers: [
                    'Content-Type' => 'application/json',
                    'X-Inertia' => 'true',
                    'Vary' => 'X-Inertia',
                ],
            );
        }

        return Response::html(
            $this->view->renderToString($this->rootView, [
                'page' => json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            ]),
        );
    }
}
