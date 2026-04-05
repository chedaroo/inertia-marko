<?php

declare(strict_types=1);

use Inertia\Inertia;
use Inertia\InertiaInterface;

return [
    'bindings' => [
        InertiaInterface::class => Inertia::class,
    ],
    'singletons' => [
        Inertia::class,
    ],
];