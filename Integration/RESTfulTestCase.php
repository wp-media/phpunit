<?php

namespace WPMedia\PHPUnit\Integration;

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

	public function set_up() {
		parent::set_up();

		$this->setUpServer();
	}
}
