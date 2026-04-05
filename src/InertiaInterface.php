<?php

declare(strict_types=1);

namespace Inertia;

use Marko\Routing\Http\Request;
use Marko\Routing\Http\Response;

interface InertiaInterface
{
    public function setRequest(Request $request): void;
    public function setRootView(string $rootView): void;
    public function setVersion(string $version): void;
    public function share(string $key, mixed $value): void;
    public function render(string $component, array $props = []): Response;
}