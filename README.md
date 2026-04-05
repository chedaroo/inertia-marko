# inertia-marko

The [Marko PHP](https://marko.build) server-side adapter for [Inertia.js](https://inertiajs.com).

## Requirements

- PHP 8.5+
- Marko PHP framework with `marko/view` and `marko/view-latte`

## Installation

```bash
composer require chedaroo/inertia-marko
```

## Usage

```php
use Inertia\InertiaInterface;
use Inertia\Middleware\HandleInertiaRequests;
use Marko\Routing\Attributes\Get;
use Marko\Routing\Attributes\Middleware;
use Marko\Routing\Http\Response;

class DashboardController
{
    public function __construct(
        private readonly InertiaInterface $inertia,
    ) {}

    #[Get('/dashboard')]
    #[Middleware(HandleInertiaRequests::class)]
    public function index(): Response
    {
        return $this->inertia->render('Dashboard', [
            'user' => 'Cheda',
        ]);
    }
}
```

## License

MIT
