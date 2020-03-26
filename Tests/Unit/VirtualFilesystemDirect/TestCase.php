<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\Unit\VirtualFilesystemTestCase;

abstract class TestCase extends VirtualFilesystemTestCase {
	protected $path_to_test_data = 'structure.php';

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		static::$path_to_fixtures_dir = WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}

	/**
	 * Gets the default virtual directory filesystem structure.
	 *
	 * @return array default structure.
	 */
	private function getDefaultVfs() {
		return [
			'wp-admin'      => [],
			'wp-content'    => [
				'mu-plugins' => [],
				'plugins'    => [
					'wp-rocket' => [],
				],
				'themes'     => [],
				'uploads'    => [],
			],
			'wp-includes'   => [],
			'wp-config.php' => '',
		];
	}
}
