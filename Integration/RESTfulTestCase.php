<?php

namespace WPMedia\PHPUnit\Integration;

use WP_Rest_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_UnitTestCase;

abstract class RESTfulTestCase extends TestCase {
	use ApiTrait;
	use RESTTrait;

	/**
	 * Name of the API credentials config file, if applicable. Set in the test or new TestCase.
	 *
	 * For example: rocketcdn.php or cloudflare.php.
	 *
	 * @var string
	 */
	protected static $api_credentials_config_file;

	public function setUp() {
		parent::setUp();

		$this->setUpServer();
	}
}
