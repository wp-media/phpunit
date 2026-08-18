<?php

// {{unit_config}} stands in for the runtime-resolved phpunit.xml.dist path; the test substitutes
// it before asserting.
return [
	'builds the base unit script when only the suite is given' => [
		'argv'     => [ 'vendor/bin/wpmedia-phpunit', 'unit' ],
		'test'     => 'unit',
		'expected' => [
			'vendor/bin/phpunit',
			'--testsuite',
			'unit',
			'--colors=auto',
			'--configuration',
			'{{unit_config}}',
		],
	],

	'returns an empty script for an unknown suite' => [
		'argv'     => [ 'vendor/bin/wpmedia-phpunit', 'bogus' ],
		'test'     => 'bogus',
		'expected' => [],
	],

	'passes through extra phpunit arguments' => [
		'argv'     => [ 'x', 'unit', '--filter', 'testSomething' ],
		'test'     => 'unit',
		'expected' => [
			'vendor/bin/phpunit',
			'--testsuite',
			'unit',
			'--colors=auto',
			'--configuration',
			'{{unit_config}}',
			'--filter',
			'testSomething',
		],
	],

	'strips the consumer path and root-dir arguments' => [
		'argv'     => [ 'x', 'unit', 'path=Tests/Custom', 'WPMEDIA_PHPUNIT_ROOT_DIR=/srv/app', '--filter', 'foo' ],
		'test'     => 'unit',
		'expected' => [
			'vendor/bin/phpunit',
			'--testsuite',
			'unit',
			'--colors=auto',
			'--configuration',
			'{{unit_config}}',
			'--filter',
			'foo',
		],
	],
];
