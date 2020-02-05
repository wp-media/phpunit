<?php

namespace WPMedia\PHPUnit\Integration;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WPMedia\PHPUnit\TestCaseTrait;
use WP_UnitTestCase;

abstract class TestCase extends WP_UnitTestCase {
	use TestCaseTrait;
	use MockeryPHPUnitIntegration;

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Cleans up the test environment after each test.
	 */
	public function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}
}
