<?php

namespace WPMedia\PHPUnit\Integration;

use Brains\Monkey\Functions;
use WPMedia\PHPUnit\VirtualFilesystemDirect;

abstract class VirtualFilesystemTestCase extends TestCase {

	/**
	 * The root virtual directory name.
	 *
	 * @var string
	 */
	protected $rootVirtualDir = 'cache';

	/**
	 * Virtual Directory filesystem structure under the root virtual directory.
	 *
	 * @var array
	 */
	protected $structure = [
		'busting'      => [
			'1' => [],
		],
		'critical-css' => [],
		'min'          => [],
		'wp-rocket'    => [
			'index.html' => '',
		],
	];

	/**
	 * Virtual directory and file permissions.
	 *
	 * @var int
	 */
	protected $permissions = 0777;

	/**
	 * Instance of the virtual filesystem.
	 *
	 * @var VirtualFilesystemDirect
	 */
	protected $filesystem;

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		parent::setUp();

		$this->filesystem = new VirtualFilesystemDirect( $this->rootVirtualDir, $this->structure, $this->permissions );
	}
}
