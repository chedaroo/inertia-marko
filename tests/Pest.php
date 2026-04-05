<?php

declare(strict_types=1);

use Marko\View\ViewInterface;
use Marko\Routing\Http\Response;

pest()->extend(Tests\TestCase::class)->in('Feature', 'Unit');

function fakeView(): ViewInterface
{
    return new class implements ViewInterface {
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