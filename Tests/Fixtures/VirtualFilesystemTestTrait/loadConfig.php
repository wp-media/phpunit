<?php

return [

	[
		'path_to_test_data' => 'VirtualFilesystemTestTrait/configs/empty.php',
		'expected'          => [
			'vfs_dir'   => '',
			'structure' => [],
			'test_data' => [],
		],
	],

	[
		'path_to_test_data' => 'VirtualFilesystemTestTrait/configs/noVfsDir.php',
		[
			'vfs_dir'   => '',
			'structure' => [
				'baz' => '',
				'bar' => '',
			],
			'test_data' => [
				[
					'foo'    => false,
					'foobar' => 10,
				],
			],
		],
	],
];
