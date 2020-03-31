<?php

namespace WPMedia\PHPUnit\Integration;

use Brains\Monkey\Functions;
use WPMedia\PHPUnit\VirtualFilesystemTestTrait;

abstract class VirtualFilesystemTestCase extends TestCase {

	use VirtualFilesystemTestTrait;

	/**
	 * Path to the Fixtures directory.
	 *
	 * @var string
	 */
	protected static $path_to_fixtures_dir;

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

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		$this->init();

		parent::setUp();
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
