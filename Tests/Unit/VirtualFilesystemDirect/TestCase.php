<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\Unit\VirtualFilesystemTestCase;

abstract class TestCase extends VirtualFilesystemTestCase {

	/**
	 * Path to the config and test data in the Fixtures directory.
	 *
	 * @var string
	 */
	protected $path_to_test_data = 'structure.php';

	/**
	 * Initializes the virtual filesystem before each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->init();
	}

	/**
	 * Gets the path to the Fixtures directory.
	 *
	 * @return string
	 */
	public function getPathToFixturesDir() {
		return WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}

	/**
	 * Loads the test data matching the given file from the Fixtures directory.
	 *
	 * @param string $file Path or filename of the test class requesting the data.
	 *
	 * @return array test data.
	 */
	protected function loadTestData( $file ) {
		return $this->getTestData( WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/', basename( $file, '.php' ) );
	}

	/**
	 * Overrides the package's WP-like default with the `Tests/{Integration,Unit}` structure
	 * that the fixtures in this suite (see {@see Test_GetListing}, {@see Test_GetDirsListing},
	 * {@see Test_GetFilesListing}) assert against as the full, exact listing of the virtual
	 * filesystem root.
	 *
	 * @return array default structure.
	 */
	public function getDefaultVfs() {
		return [
			'Tests' => [
				'Integration' => [],
				'Unit'        => [],
			],
		];
	}
}
