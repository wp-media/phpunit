# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Fixed

- `@runInSeparateProcess` tests now survive being run through the `wpmedia-phpunit` bin.
  Previously, PHPUnit's process-isolation machinery re-required the self-executing
  `wpmedia-phpunit` bin, `BootstrapManager.php`, and Composer's `vendor/bin/phpunit` proxy into
  the isolated child, corrupting its result (`Test was run in child process and ended
  unexpectedly`). `BootstrapManager::registerIsolationExcludeList()` now excludes these files from
  PHPUnit's isolation exclude list. See issue #51.
- `Tests/{Unit,Integration}/init-tests.php` no longer emit a `Warning: Constant … already defined`
  notice when the real bin self-targets this package's own suite.

### Changed

- **Observable CLI-output change:** `--colors` now defaults to `auto` (was `always`) for
  `composer test-unit`, `test-integration`, and `test-integration-admin`, and for the
  `wpmedia-phpunit` bin's generated PHPUnit invocation. Piping test output to a file or CI log no
  longer emits raw ANSI escape codes. Consumers who rely on forced color output should pass
  `--colors=always` explicitly.
