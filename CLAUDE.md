# CLAUDE.md

Guidance for AI agents working in this repository.

## What this is

`wp-media/phpunit` is a **reusable library** (not a plugin) that bootstraps PHPUnit
unit and WordPress integration test suites for WP Media projects. Production code
lives in `src/` (PSR-4 `WPMedia\PHPUnit\`); the package's own tests live in `Tests/`.

## Architecture

- **Consumer entry point** — the `wpmedia-phpunit` bin (Composer-symlinked into a consumer's
  `vendor/bin/`) delegates to `BootstrapManager` (`src/BootstrapManager.php`), which parses argv
  (`WPMEDIA_PHPUNIT_ROOT_DIR=`, `path=`, `--group`), derives the `WPMEDIA_PHPUNIT_ROOT_DIR` /
  `WPMEDIA_PHPUNIT_ROOT_TEST_DIR` constants, picks the `phpunit.xml.dist` to use, and hands off to
  PHPUnit. Downstream repos run their suites via `vendor/bin/wpmedia-phpunit unit|integration`.
- **Bootstraps** — `src/{Unit,Integration}/bootstrap.php` load the autoloader, Patchwork, and
  Brain\Monkey (unit) or Yoast WPIntegration (integration), then optionally require an add-on
  bootstrap (`WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR`) and the consumer's own
  `Tests/{Unit,Integration}/bootstrap.php`. Each bootstrap is **self-locating**: if
  `WPMEDIA_PHPUNIT_ROOT_DIR` is not already defined (e.g. the file is required directly rather than
  through the bin), it requires `BootstrapManager` and calls `setupConstants('unit'|'integration')`
  to derive the constants itself before proceeding.
- **Public API consumers extend** — base `Unit\TestCase` / `Integration\TestCase`, plus
  `VirtualFilesystemTestCase`, `AdminTestCase`, `AjaxTestCase`, `RESTfulTestCase`, `RESTVfsTestCase`,
  and the traits (`ArrayTrait`, `TestCaseTrait`, `VirtualFilesystemTestTrait`, `ApiTrait`,
  `HttpRequestTrait`, `RESTTrait`). Changing these signatures is a breaking change for downstream repos.
- **Integration `--group` has side effects** — `src/Integration/bootstrap.php` defines `WP_ADMIN`
  for `--group AdminOnly` and `MULTISITE` for `--group Multisite`. That is why the integration
  suite is split into separate composer scripts.

### Test layout

One class per method: `Tests/{Unit,Integration}/<Subject>/<method>.php` holding a `Test_<Method>`
class (a per-group abstract `TestCase.php` holds shared setup), with data providers in the mirrored
`Tests/Fixtures/<Subject>/` directory (resolved by `TestCaseTrait::getTestData()`, or by
`TestCaseTrait::configTestData()`, which self-locates the fixture matching the test class and returns
its `'test_data'` key — the config-driven variant added in #53). Test namespace is
PSR-4 `WPMedia\PHPUnit\Tests\`. New per-method test files follow the existing style (no per-method
doc comments) and stay **out** of the `phpcs.xml.dist` scope, alongside the other test files.

> **Self-test caveat:** the package's own suite bootstraps through
> `Tests/{Unit,Integration}/init-tests.php`, which defines the two constants directly and requires
> `src/{Unit,Integration}/bootstrap.php` — bypassing the bin and most of `BootstrapManager`. A green
> suite therefore does not by itself prove the consumer entry point; `BootstrapManager`'s argv
> parsing is covered explicitly in `Tests/Unit/BootstrapManager/`.

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

**phpcs coverage is intentionally partial.** `phpcs.xml.dist` enumerates a specific allow-list of
files rather than scanning the whole tree (see issue #39): only files already brought up to the WP
Media standard are listed, so CI stays green on the ~20 pre-existing files that have not been
migrated. Add files to that list opportunistically as they are cleaned up — do **not** widen the
scope wholesale (that would fail CI on all the unmigrated files at once). Most test files, including
the per-method files under `Tests/`, stay out of scope by design. PHPStan, by contrast, analyzes the
full `src/` + `Tests/` set with a baseline, so new code must be PHPStan-clean.
