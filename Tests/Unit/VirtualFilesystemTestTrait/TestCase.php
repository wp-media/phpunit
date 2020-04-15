<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemTestTrait;

use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;
use WPMedia\PHPUnit\VirtualFilesystemTestTrait;

abstract class TestCase extends BaseTestCase {

	use VirtualFilesystemTestTrait;

	/**
	 * Path to the config and test data in the Fixtures directory.
	 * Set this path in each test class.
	 *
	 * @var string
	 */
	protected $path_to_test_data;

	/**
	 * The root virtual directory name.
	 *
	 * @var string
	 */
	protected $rootVirtualDir = 'public';

	/**
	 * Virtual directory and file permissions.
	 *
	 * @var int
	 */
	protected $permissions = 0777;

	public function getPathToFixturesDir() {
		return WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}

	/**
	 * Gets the default virtual directory filesystem structure.
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
