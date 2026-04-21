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

## Development

### Devcontainer (recommended)

Open the repo in VS Code and choose **Reopen in Container** — it will build a PHP 8.5 environment and run `composer install` automatically. This also works with GitHub Codespaces.

### Local setup

Requires PHP 8.5+ and [Composer](https://getcomposer.org).

```bash
git clone https://github.com/chedaroo/inertia-marko.git
cd inertia-marko
composer install
```

### Running tests

```bash
composer test
```

### Checking code style

```bash
composer format:check
```

To auto-fix:

```bash
composer format
```

## License

MIT
