<?php

namespace WPMedia\PHPUnit;

/**
 * Initialize the test suite.
 */
function init_test_suite() {
	check_readiness();

	// Load the Composer autoloader.
	require_once WPMEDIA_PHPUNIT_ROOT_DIR . '/vendor/autoload.php';
	require_once __DIR__ . '/TestCaseTrait.php';

	// Load Patchwork before everything else in order to allow us to redefine WordPress, 3rd party, or any other non-native PHP functions.
	require_once WPMEDIA_PHPUNIT_ROOT_DIR . '/vendor/antecedent/patchwork/Patchwork.php';
}

/**
 * Check the system's readiness to run the tests.
 *
 * @param string|null $php_version PHP version to validate. Defaults to the running version.
 */
function check_readiness( $php_version = null ) {
	$php_version = $php_version ?: phpversion();

	if ( version_compare( $php_version, '7.4.0', '<' ) ) {
		trigger_error( 'Test Suite requires PHP 7.4 or higher.', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Valid use case for our testing suite.
	}

	if ( ! file_exists( WPMEDIA_PHPUNIT_ROOT_DIR . '/vendor/autoload.php' ) ) {
		trigger_error( 'Whoops, we need Composer before we start running tests.  Please type: `composer install`.  When done, try running `phpunit` again.', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Valid use case for our testing suite.
	}
}

