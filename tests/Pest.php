<?php

declare(strict_types=1);

use Marko\Routing\Http\Response;
use Marko\View\ViewInterface;
use Tests\TestCase;

pest()->extend(TestCase::class)->in('Feature', 'Unit');

function fakeView(): ViewInterface
{
    return new class () implements ViewInterface
    {
        public function render(string $template, array $data = []): Response
        {
            return Response::html('');
        }

        public function renderToString(string $template, array $data = []): string
        {
            return '<h1>Fake View</h1>';
        }
    };
}
