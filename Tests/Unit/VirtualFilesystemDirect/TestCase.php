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
				'bootstrap.php' => 'Donec turpis ante, aliquam vitae egestas ac, rhoncus ut quam.',
				'SomeClass'     => [
					'getFile.php' => '',
				],
			],
			'includes'     => [],
			'TestCase.php' => 'Maecenas eget erat ligula.',
		],
		'baz'   => [
			'index.html' => 'Lorem ipsum dolor sit amet.',
		],
	];
	protected $permissions = 0777;

	protected function setUp() {
		parent::setUp();

		$this->filesystem = new VirtualFilesystemDirect( 'cache', $this->structure, $this->permissions );
	}
}
