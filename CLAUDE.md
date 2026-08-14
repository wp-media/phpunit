# CLAUDE.md

Guidance for AI agents working in this repository.

## What this is

`wp-media/phpunit` is a **reusable library** (not a plugin) that bootstraps PHPUnit
unit and WordPress integration test suites for WP Media projects. Production code
lives in `src/` (PSR-4 `WPMedia\PHPUnit\`); the package's own tests live in `Tests/`.

## Running the tests — use wp-env

`wp-env` is the default, supported way to run this package's tests locally. It provides
a Dockerized WordPress + MySQL with Composer/PHPUnit/WP-CLI preinstalled and exposes the
WordPress test suite via `WP_TESTS_DIR`, which the integration bootstrap
(`src/Integration/bootstrap.php`) consumes through Yoast's `get_path_to_wp_test_dir()`.
No manual DB or WP test-suite install is needed.

First-time setup and run:

```bash
npm install          # installs @wordpress/env
npm run env:start    # boots WordPress + MySQL (Docker must be running)
npm run env:install  # composer install inside the tests-cli container
npm run test:php     # unit + integration + admin integration
```

Individual suites: `npm run test:php:unit`, `npm run test:php:integration`,
`npm run test:php:integration-admin`. Stop with `npm run env:stop`.

### Key facts for agents

- **Requires Docker running** and Node.js. If Docker is unavailable, unit tests (fully
  mocked, no WordPress) can run on the host with `composer test-unit`; integration tests
  cannot run without wp-env or an equivalent WP test-suite install.
- Config is `.wp-env.json`: latest WordPress, PHP 8.3, repo mounted via `mappings` into
  `wp-content/plugins/wpmedia-phpunit` (**not** listed under `plugins`, since a library
  has no plugin header to activate).
- **Ports are 8890 (dev) / 8891 (tests)** instead of wp-env's defaults 8888/8889, which
  collide with Local by Flywheel. For other machine-specific overrides, create a
  `.wp-env.override.json` (gitignored) rather than editing `.wp-env.json`.
- Change PHP version per-run with `WP_ENV_PHP_VERSION=8.1 npm run env:start`.

## CI

CI does **not** use wp-env. `.github/workflows/tests.yml` runs the PHP 8.0–8.5 matrix and
`tests_legacy.yml` runs PHP 7.4 / WP 5.9, both via the `wp-media/workflows/setup-wp-tests`
composite action. Keep the `composer` scripts (`test-unit`, `test-integration`,
`test-integration-admin`, `run-tests`) working, since both wp-env and CI invoke them.

## Static analysis

- `composer phpcs` / `composer phpcs:fix` — WordPress Coding Standards (`phpcs.xml.dist`).
- `composer phpstan` — PHPStan (`phpstan.neon.dist`, baseline in `phpstan-baseline.neon`).
