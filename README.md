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

## Running Your Repo's Tests

Composer symlinks this package's runner to `vendor/bin/wpmedia-phpunit`. Point your repo's own
Composer scripts at it, passing the suite to run:

```json
"scripts": {
	"test-unit": "wpmedia-phpunit unit",
	"test-integration": "wpmedia-phpunit integration"
}
```

The runner (`WPMedia\PHPUnit\BootstrapManager`) resolves where your tests and configuration live,
then hands off to PHPUnit. It accepts a few optional arguments:

- `WPMEDIA_PHPUNIT_ROOT_DIR=<path>` — the root of the repo under test. Defaults to four levels up
  from the package's `src/` (i.e. your project root when this package is installed under
  `vendor/`). Pass `.` to target this package itself.
- `path=<dir>` — the test directory. Defaults to `Tests/Unit` or `Tests/Integration` depending on
  the suite.
- `--group <name>` — the standard PHPUnit group selector. Two groups are special during
  integration bootstrapping: `AdminOnly` defines `WP_ADMIN` (so `is_admin()` is `true`) and
  `Multisite` defines `MULTISITE`.

Any other arguments (e.g. `--filter`) are forwarded to PHPUnit unchanged. If your repo ships its
own `phpunit.xml.dist` in the test directory, it is used; otherwise the bundled default applies.

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
