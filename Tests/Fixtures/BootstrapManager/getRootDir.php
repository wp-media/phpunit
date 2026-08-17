<?php

// The install-location-dependent expectations are tokens resolved by the test against the
// package's actual src/ location: {{consumer_root}} = four levels up from src/ (the project root
// when installed under vendor/), {{package_root}} = this package's own root.
return [
	'no root given walks up to the consumer root' => [
		'root'     => false,
		'expected' => '{{consumer_root}}',
	],

	'"." resolves to the package root' => [
		'root'     => [ 'WPMEDIA_PHPUNIT_ROOT_DIR' => '.' ],
		'expected' => '{{package_root}}',
	],

	'leading slash is stripped from an explicit root' => [
		'root'     => [ 'WPMEDIA_PHPUNIT_ROOT_DIR' => '/var/www/plugin' ],
		'expected' => 'var/www/plugin',
	],

	'a relative root is returned unchanged' => [
		'root'     => [ 'WPMEDIA_PHPUNIT_ROOT_DIR' => 'relative/path/to/plugin' ],
		'expected' => 'relative/path/to/plugin',
	],
];
