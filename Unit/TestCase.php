<?php

namespace WPMedia\PHPUnit\Unit;

use WPMedia\PHPUnit\TestCaseTrait;
use Yoast\WPTestUtils\BrainMonkey\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	use TestCaseTrait;

	/**
	 * Set to true in root TestCase to stub polyfills in setUpBeforeClass().
	 *
	 * @var bool
	 */
	protected static $stubPolyfills = false;

	/**
	 * Prepares the test environment before test class runs.
	 */
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		if ( static::$stubPolyfills ) {
			static::stubPolyfills();
		}
	}

	/**
	 * Prepares the test environment before each test.
	 */
	protected function set_up() {
        parent::set_up();
    }

	/**
	 * Cleans up the test environment after each test.
	 */
	protected function tear_down() {
        parent::tear_down();
    }
}
