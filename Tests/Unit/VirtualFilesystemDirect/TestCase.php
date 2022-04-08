<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\Unit\VirtualFilesystemTestCase;

abstract class TestCase extends VirtualFilesystemTestCase {
	protected $path_to_test_data = 'structure.php';

	protected function setUp(): void {
		parent::setUp();

		$this->init();
	}

	public function getPathToFixturesDir() {
		return WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}

	protected function loadTestData( $file ) {
		return $this->getTestData( WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/', basename( $file, '.php' ) );
	}
}
