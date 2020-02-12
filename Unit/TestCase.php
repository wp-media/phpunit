<?php

namespace WPMedia\PHPUnit\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use WPMedia\PHPUnit\TestCaseTrait;

abstract class TestCase extends PHPUnitTestCase {
	use MockeryPHPUnitIntegration;
	use TestCaseTrait;

	/**
	 * Set to true in root TestCase to stub polyfills in setUpBeforeClass().
	 *
	 * @var bool
	 */
	protected static $stubPolyfills = false;

	/**
	 * Set to true in root TestCase to mock the common WP Functions in the setUp().
	 *
	 * @var bool
	 */
	protected static $mockCommonWpFunctionsInSetUp = false;

	/**
	 * Prepares the test environment before test class runs.
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		if ( static::$stubPolyfills ) {
			static::stubPolyfills();
		}
	}

	/**
	 * Prepares the test environment before each test.
	 */
	protected function setUp() {
		parent::setUp();
		Monkey\setUp();

		if ( static::$mockCommonWpFunctionsInSetUp ) {
			$this->mockCommonWpFunctions();
		}
	}

	/**
	 * Cleans up the test environment after each test.
	 */
	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Mock common WP functions.
	 */
	protected function mockCommonWpFunctions() {
		Functions\stubs(
			[
				'__',
				'esc_attr__',
				'esc_html__',
				'_x',
				'esc_attr_x',
				'esc_html_x',
				'_n',
				'_nx',
				'esc_attr',
				'esc_html',
				'esc_textarea',
				'esc_url',
			]
		);

		$functions = [
			'_e',
			'esc_attr_e',
			'esc_html_e',
			'_ex',
		];

		foreach ( $functions as $function ) {
			Functions\when( $function )->echoArg();
		}
	}
}
