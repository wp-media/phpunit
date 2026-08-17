<?php

return [
	'key=value argument returns its index and value' => [
		'argv'     => [ 'vendor/bin/wpmedia-phpunit', 'unit', 'path=Tests/Foo' ],
		'key'      => 'path',
		'expected' => [ 'index' => 2, 'path' => 'Tests/Foo' ],
	],

	'root-dir argument keeps its absolute path value' => [
		'argv'     => [ 'x', 'integration', 'WPMEDIA_PHPUNIT_ROOT_DIR=/var/www/plugin' ],
		'key'      => 'WPMEDIA_PHPUNIT_ROOT_DIR',
		'expected' => [ 'index' => 2, 'WPMEDIA_PHPUNIT_ROOT_DIR' => '/var/www/plugin' ],
	],

	'flag without an equals sign is its own value' => [
		'argv'     => [ 'x', 'integration', '--group', 'AdminOnly' ],
		'key'      => '--group',
		'expected' => [ 'index' => 2, '--group' => '--group' ],
	],

	'absent key returns false' => [
		'argv'     => [ 'x', 'unit' ],
		'key'      => 'path',
		'expected' => false,
	],
];
