<?php

return [
	'vfs_dir'   => 'public/',

	// Virtual filesystem structure.
	'structure' => [
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
	],

	// Test data.
	'test_data' => [],
];
