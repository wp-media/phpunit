<?php

declare(strict_types=1);

return [
	'environment variable is set'                => [
		'env_name'             => 'WPMEDIA_PHPUNIT_TEST_CREDENTIAL',
		'env_value'            => 'from_env',
		'config_filename'      => null,
		'config_file_contents' => null,
		'credential_name'      => 'WPMEDIA_PHPUNIT_TEST_CREDENTIAL',
		'expected'             => 'from_env',
	],

	'no config file is set'                      => [
		'env_name'             => null,
		'env_value'            => null,
		'config_filename'      => null,
		'config_file_contents' => null,
		'credential_name'      => 'WPMEDIA_PHPUNIT_TEST_UNSET_CONSTANT',
		'expected'             => '',
	],

	'config file is not readable'                => [
		'env_name'             => null,
		'env_value'            => null,
		'config_filename'      => 'missing-credentials.php',
		'config_file_contents' => null,
		'credential_name'      => 'WPMEDIA_PHPUNIT_TEST_UNSET_CONSTANT',
		'expected'             => '',
	],

	'constant is defined in the config file'     => [
		'env_name'             => null,
		'env_value'            => null,
		'config_filename'      => 'credentials.php',
		'config_file_contents' => "<?php define( 'WPMEDIA_PHPUNIT_TEST_CONSTANT', 'from_constant' );",
		'credential_name'      => 'WPMEDIA_PHPUNIT_TEST_CONSTANT',
		'expected'             => 'from_constant',
	],

	'constant is not defined in the config file' => [
		'env_name'             => null,
		'env_value'            => null,
		'config_filename'      => 'empty-credentials.php',
		'config_file_contents' => '<?php // No constants defined here.',
		'credential_name'      => 'WPMEDIA_PHPUNIT_TEST_UNDEFINED_CONSTANT',
		'expected'             => '',
	],
];
