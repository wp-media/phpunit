<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\VirtualFilesystemDirect;
use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	protected $filesystem;
	protected $structure = [
		'Tests' => [
			'Integration'  => [],
			'Unit'         => [
				'bootstrap.php' => '',
				'SomeClass'     => [
					'getFile.php' => '',
				],
			],
			'includes'     => [],
			'TestCase.php' => 'some value',
		],
		'baz'   => [
			'index.html' => '',
		],
	];

	protected function setUp() {
		parent::setUp();

		$this->filesystem = new VirtualFilesystemDirect( 'cache', $this->structure );
	}
}
