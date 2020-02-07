<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\VirtualFilesystemDirect;
use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	protected $filesystem;

	protected function setUp() {
		parent::setUp();

		$this->filesystem = new VirtualFilesystemDirect(
			'cache',
			[
				'busting'      => [
					'1' => [],
				],
				'critical-css' => [],
				'min'          => [],
				'wp-rocket'    => [
					'index.html' => '',
				],
			]
		);
	}
}
