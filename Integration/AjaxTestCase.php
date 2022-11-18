<?php

namespace WPMedia\PHPUnit\Integration;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WPMedia\PHPUnit\TestCaseTrait;
use WPAjaxDieContinueException;
use WP_Ajax_UnitTestCase;

abstract class AjaxTestCase extends WP_Ajax_UnitTestCase {
	use ApiTrait;
	use MockeryPHPUnitIntegration;
	use TestCaseTrait;

	/**
	 * AJAX Action. Change this value in each test class.
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Name of the API credentials config file, if applicable. Set in the test or new TestCase.
	 *
	 * For example: rocketcdn.php or cloudflare.php.
	 *
	 * @var string
	 */
	protected static $api_credentials_config_file;

	/**
	 * Prepares the test environment before each test.
	 */
	public function set_up() {
		parent::set_up();
		Monkey\setUp();
	}

	/**
	 * Cleans up the test environment after each test.
	 */
	public function tear_down() {
		Monkey\tearDown();
		parent::tear_down();
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
		} catch ( WPAjaxDieContinueException $e ) {
		}

		return $this->getResponse();
	}

	/**
	 * Get the AJAX Response.
	 */
	protected function getResponse() {
		var_export ( 'response:' );
		var_export( $this->_last_response );
		return json_decode( $this->_last_response );
	}
}
