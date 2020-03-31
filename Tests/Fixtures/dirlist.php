<?php

$config              = require __DIR__ . '/structure.php';
$config['test_data'] = [
	[
		'Tests/Unit/',
		[
			'bootstrap.php' => [
				'name'        => 'bootstrap.php',
				'perms'       => 'urw-rw-rw-',
				'permsn'      => '0666',
				'number'      => false,
				'owner'       => null,
				'group'       => null,
				'size'        => 61,
				'lastmodunix' => null,
				'lastmod'     => null,
				'time'        => null,
				'type'        => 'f',
			],
			'SomeClass'     => [
				'name'        => 'SomeClass',
				'perms'       => 'urwxrwxrwx',
				'permsn'      => '0777',
				'number'      => false,
				'owner'       => null,
				'group'       => null,
				'size'        => 0,
				'lastmodunix' => null,
				'lastmod'     => null,
				'time'        => null,
				'type'        => 'd',
				'files'       => [],
			],
		],
	],
];

return $config;
