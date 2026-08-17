<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

use WPMedia\PHPUnit\BootstrapManager as Subject;
use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;

/**
 * Base test case for the {@see \WPMedia\PHPUnit\BootstrapManager} argv-parsing helpers.
 *
 * These tests exercise the consumer entry point (the `wpmedia-phpunit` bin + BootstrapManager)
 * that the package's own suite bypasses: `Tests/{Unit,Integration}/init-tests.php` defines the
 * constants directly and requires `src/{Unit,Integration}/bootstrap.php`, so BootstrapManager is
 * otherwise never run. See CLAUDE.md ("Architecture").
 */
abstract class TestCase extends BaseTestCase {

	/**
	 * Snapshot of $_SERVER['argv'] taken before each test.
	 *
	 * @var array|null
	 */
	private $original_argv;

	/**
	 * Snapshot of $_SERVER['argc'] taken before each test.
	 *
	 * @var int|null
	 */
	private $original_argc;

	/**
	 * BootstrapManager reads and mutates the global argv/argc while resolving the consumer's
	 * run configuration. Snapshot them before each test so nothing leaks into other tests.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->original_argv = isset( $_SERVER['argv'] ) ? $_SERVER['argv'] : null;
		$this->original_argc = isset( $_SERVER['argc'] ) ? $_SERVER['argc'] : null;
	}

	/**
	 * Restores the global argv/argc after each test.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		if ( null === $this->original_argv ) {
			unset( $_SERVER['argv'] );
		} else {
			$_SERVER['argv'] = $this->original_argv;
		}

		if ( null === $this->original_argc ) {
			unset( $_SERVER['argc'] );
		} else {
			$_SERVER['argc'] = $this->original_argc;
		}

		parent::tearDown();
	}

	/**
	 * Simulates the command line that BootstrapManager reads.
	 *
	 * @param array $argv Command-line arguments to simulate.
	 *
	 * @return void
	 */
	protected function setArgv( array $argv ) {
		$_SERVER['argv'] = $argv;
		$_SERVER['argc'] = count( $argv );
	}

	/**
	 * Invokes a protected/private static method on BootstrapManager.
	 *
	 * @param string $method Method name.
	 * @param array  $args   Arguments to pass through.
	 *
	 * @return mixed the method's return value.
	 */
	protected function invoke( $method, array $args = [] ) {
		return $this->get_reflective_method( $method, Subject::class )->invokeArgs( null, $args );
	}
}
