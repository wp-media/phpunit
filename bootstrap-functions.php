<?php

namespace WPMedia\PHPUnit;

/**
 * Initialize the test suite.
 *
 * @param string $test_suite Directory name of the test suite. Default is 'Unit'.
 */
function init_test_suite( $test_suite = 'Unit' ) {
	init_constants( $test_suite );

	check_readiness();

	// Load the Composer autoloader.
	require_once WPMEDIA_PHPUNIT_PLUGIN_DIR . '/vendor/autoload.php';
	require_once __DIR__ . '/TestCaseTrait.php';

	// Load Patchwork before everything else in order to allow us to redefine WordPress, 3rd party, or any other non-native PHP functions.
	require_once WPMEDIA_PHPUNIT_PLUGIN_DIR . 'vendor/antecedent/patchwork/Patchwork.php';
}

/**
 * Check the system's readiness to run the tests.
 */
function check_readiness() {
	if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
		trigger_error( 'Test Suite requires PHP 5.6 or higher.', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Valid use case for our testing suite.
	}

	if ( ! file_exists( WPMEDIA_PHPUNIT_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
		trigger_error( 'Whoops, we need Composer before we start running tests.  Please type: `composer install`.  When done, try running `phpunit` again.', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Valid use case for our testing suite.
	}
}

/**
 * Initialize the constants.
 *
 * @param string $test_suite_folder Directory name of the test suite.
 */
function init_constants( $test_suite_folder ) {
	define( 'WPMEDIA_PHPUNIT_PLUGIN_DIR', dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR );
	define( 'WPMEDIA_PHPUNIT_TESTS_DIR', __DIR__ . DIRECTORY_SEPARATOR );
	define( 'WPMEDIA_PHPUNIT_TESTS_ROOT', WPMEDIA_PHPUNIT_TESTS_DIR . $test_suite_folder );

	if ( 'Unit' === $test_suite_folder && ! defined( 'ABSPATH' ) ) {
		define( 'ABSPATH', WPMEDIA_PHPUNIT_PLUGIN_DIR );
	}
}
