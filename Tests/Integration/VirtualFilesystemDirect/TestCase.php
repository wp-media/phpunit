<?php

namespace WPMedia\PHPUnit\Tests\Integration\VirtualFilesystemDirect;

use WPMedia\PHPUnit\Integration\VirtualFilesystemTestCase;

abstract class TestCase extends VirtualFilesystemTestCase {
	protected $path_to_test_data = 'structure.php';

	public function set_up() {
		parent::set_up();

		$this->init();
	}

	public function getPathToFixturesDir() {
		return WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}
}
