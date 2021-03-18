<?php

namespace WPMedia\PHPUnit\Integration;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WPMedia\PHPUnit\TestCaseTrait;
use Yoast\WPTestUtils\WPIntegration\TestCase as WPIntegrationTestCase;

abstract class TestCase extends WPIntegrationTestCase {
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

	public function return_0() {
		return 0;
	}

	public function return_1() {
		return 1;
	}

	public function return_false() {
		return false;
	}

	public function return_true() {
		return true;
	}

	public function return_empty_array() {
		return [];
	}
}
