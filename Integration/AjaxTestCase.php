<?php

namespace WPMedia\PHPUnit\Integration;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WPMedia\PHPUnit\TestCaseTrait;
use WPAjaxDieContinueException;
use WPAjaxDieStopException;
use WP_Ajax_UnitTestCase;

abstract class AjaxTestCase extends WP_Ajax_UnitTestCase {
	use TestCaseTrait;
	use MockeryPHPUnitIntegration;

	/**
	 * AJAX Action. Change this value in each test class.
	 *
	 * @var string
	 */
	protected $action;

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

	/**
	 * Calls the Ajax Action.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed the response.
	 */
	protected function callAjaxAction() {
		try {
			$this->_handleAjax( $this->action );
		} catch ( WPAjaxDieContinueException $e ) {}

		return $this->getResponse();
	}

	/**
	 * Get the AJAX Response.
	 */
	protected function getResponse() {
		return json_decode( $this->_last_response );
	}
}
