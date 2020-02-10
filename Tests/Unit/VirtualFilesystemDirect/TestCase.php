<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\Unit\VirtualFilesystemTestCase;

abstract class TestCase extends VirtualFilesystemTestCase {

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
}
