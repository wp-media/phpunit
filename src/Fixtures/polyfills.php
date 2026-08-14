<?php

/**
 * Polyfill functions required by tests.
 *
 * This package is plugin-agnostic and no longer ships plugin-specific polyfills (e.g. WP Rocket's
 * `rocket_get_constant()` / `rocket_has_constant()`). If your consuming project needs to stub
 * plugin-specific functions, provide your own fixtures file and require it from your project's
 * `bootstrap.php`, or override `TestCaseTrait::stubPolyfills()`.
 *
 * @see https://github.com/wp-media/phpunit/issues/35
 */
