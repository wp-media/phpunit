<?php

$structure = [
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

return [
	'testShouldInitOriginalsWhenVfsDirNotDefined' => [
		'config'         => [
			'structure' => $structure,
		],
		'expected_dirs'  => [
			'Tests',
			'Tests/Integration',
			'Tests/Unit',
			'Tests/Unit/SomeClass',
			'Tests/includes',
			'baz',
		],
		'expected_files' => [
			'Tests/Integration',
			'Tests/Unit/bootstrap.php',
			'Tests/Unit/SomeClass/getFile.php',
			'Tests/includes',
			'Tests/TestCase.php',
			'baz/index.html',
		],
	],

	'testShouldInitOriginalsWhenVfsDirEmpty' => [
		'config'         => [
			'vfs_dir' => '',
			'structure' => $structure,
		],
		'expected_dirs'  => [
			'Tests',
			'Tests/Integration',
			'Tests/Unit',
			'Tests/Unit/SomeClass',
			'Tests/includes',
			'baz',
		],
		'expected_files' => [
			'Tests/Integration',
			'Tests/Unit/bootstrap.php',
			'Tests/Unit/SomeClass/getFile.php',
			'Tests/includes',
			'Tests/TestCase.php',
			'baz/index.html',
		],
	],

	'testShouldInitOriginalsWhenVfsDirIsRoot' => [
		'config'         => [
			'vfs_dir' => '/',
			'structure' => $structure,
		],
		'expected_dirs'  => [
			'Tests',
			'Tests/Integration',
			'Tests/Unit',
			'Tests/Unit/SomeClass',
			'Tests/includes',
			'baz',
		],
		'expected_files' => [
			'Tests/Integration',
			'Tests/Unit/bootstrap.php',
			'Tests/Unit/SomeClass/getFile.php',
			'Tests/includes',
			'Tests/TestCase.php',
			'baz/index.html',
		],
	],

	'testShouldInitOriginalsWhenVfsDirDefined_baz' => [
		'config'         => [
			'vfs_dir'   => 'baz',
			'structure' => $structure,
		],
		'expected_dirs'  => [],
		'expected_files' => [
			'baz/index.html',
		],
	],

	'testShouldInitOriginalsWhenVfsDirDefined_TestsUnit' => [
		'config'         => [
			'vfs_dir'   => 'Tests/Unit/',
			'structure' => $structure,
		],
		'expected_dirs'  => [
			'Tests/Unit/SomeClass',
		],
		'expected_files' => [
			'Tests/Unit/bootstrap.php',
			'Tests/Unit/SomeClass/getFile.php',
		],
	],
];
