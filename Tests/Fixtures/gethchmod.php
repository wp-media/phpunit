<?php

$config              = require __DIR__ . '/structure.php';
$config['test_data'] = [
	[
		'doesnotexist.html',
		'u---------',
	],
	[
		'baz/doesnotexist.html',
		'u---------',
	],
	[
		'Tests/Unit/bootstrap.php',
		'urw-rw-rw-',
	],
	[
		'Tests/TestCase.php',
		'urw-rw-rw-',
	],
];

return $config;
