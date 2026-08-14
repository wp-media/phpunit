# Common PHPUnit Unit and Integration Bootstrapper

This reusable package bootstraps our PHPUnit unit and integration tests. It includes:

- bootstrapping for both Unit and Integration tests
- `phpunit.xml.dist` for each test suite
- `TestCase` for each test suite

This means your repo only needs its tests. w00t!

## Configuring Composer in Your Repo

In your repo's `composer.json` file, add the following `"require-dev"` dependency:

```json
"wp-media/phpunit": "^2.0"
```

## Custom Bootstrapping Your Repo

Sometimes you need a custom bootstrapping solution in your repo, such as loading a factory, handling licensing, etc. Here are the steps to get you rolling:

1. Add a `bootstrap.php` file in `Unit` or `Integration` directory.
2. In your `Tests/Integration/bootstrap.php` file, add the following code to it:

```php
tests_add_filter(
	'muplugins_loaded',
	function() {
		// Do your bootstrapping work here.
	}
);
```

## Custom Test Case

When you need to customize the test case, extend off of the base test cases in this package:

- For a custom integration, extend off of `WPMedia\PHPUnit\Integration\TestCase`.
- For a custom unit, extend off of `WPMedia\PHPUnit\Unit\TestCase`.

## Running This Package's Tests

The default, supported way to run this package's own unit and integration tests locally is [`wp-env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/). It spins up a disposable, Dockerized WordPress + MySQL environment with Composer, PHPUnit, and WP-CLI preinstalled, and exposes the WordPress PHPUnit test suite (`WP_TESTS_DIR`) automatically — no manual database or test-suite install required.

### Prerequisites

- [Docker](https://www.docker.com/) installed and running
- [Node.js](https://nodejs.org/) (used only to run `wp-env`)

### Setup

```bash
npm install          # installs @wordpress/env
npm run env:start    # boots WordPress + MySQL (first run downloads images)
npm run env:install  # installs Composer deps inside the container
```

### Running the suites

```bash
npm run test:php                    # unit + integration + admin integration
npm run test:php:unit               # unit only
npm run test:php:integration        # integration only
npm run test:php:integration-admin  # AdminOnly integration group
```

Stop the environment with `npm run env:stop` (or `npm run env:destroy` to remove it entirely).

The environment defaults to the latest WordPress on PHP 8.3 (see `.wp-env.json`). To try another PHP version, override it per-run, e.g. `WP_ENV_PHP_VERSION=8.1 npm run env:start`, or add a `.wp-env.override.json`.

> Unit tests are fully mocked and need no WordPress, so they can also be run directly on the host with `composer test-unit`. Integration tests require a WordPress install and are best run through `wp-env`.
