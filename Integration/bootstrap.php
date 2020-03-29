<?php

namespace WPMedia\PHPUnit\Integration;

use WPMedia\PHPUnit\BootstrapManager;
use function WPMedia\PHPUnit\init_test_suite;

require_once dirname( dirname( __FILE__ ) ) . '/bootstrap-functions.php';
init_test_suite( 'Integration' );

/**
 * Get the WordPress' tests suite directory.
 *
 * @return string Returns The directory path to the WordPress testing environment.
 */
function get_wp_tests_dir() {
	$tests_dir = getenv( 'WP_TESTS_DIR' );

	// Travis CI & Vagrant SSH tests directory.
	if ( empty( $tests_dir ) ) {
		$tests_dir = '/tmp/wordpress-tests-lib';
	}

	// If the tests' includes directory does not exist, try a relative path to Core tests directory.
	if ( ! file_exists( $tests_dir . '/includes/' ) ) {
		$tests_dir = '../../../../../../../../tests/phpunit';
	}

	// Check it again. If it doesn't exist, stop here and post a message as to why we stopped.
	if ( ! file_exists( $tests_dir . '/includes/' ) ) {
		trigger_error( 'Unable to run the integration tests, because the WordPress test suite could not be located.', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Valid use case for our testing suite.
	}

	// Strip off the trailing directory separator, if it exists.
	return rtrim( $tests_dir, DIRECTORY_SEPARATOR );
}

/**
 * Bootstraps the integration testing environment with WordPress.
 *
 * @param string $wp_tests_dir The directory path to the WordPress testing environment.
 */
function bootstrap_integration_suite( $wp_tests_dir ) {
	// Set MULTISITE constant when running the Multisite group of tests.
	if ( BootstrapManager::isGroup( 'Multisite' ) && ! defined( 'MULTISITE' ) ) {
		define( 'MULTISITE', true );
	}

	// Give access to tests_add_filter() function.
	require_once $wp_tests_dir . '/includes/functions.php';

	tests_add_filter(
		'muplugins_loaded',
		function() {
			// Set WP_ADMIN constant when running the AdminOnly group of tests.
			// This is necessary to set is_admin() for Rocket to load all the admin files.
			if ( BootstrapManager::isGroup( 'AdminOnly' ) && ! defined( 'WP_ADMIN' ) ) {
				define( 'WP_ADMIN', true );
			}
		},
		9
	);

	// Bootstrap the plugin.
	if ( is_readable( WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/bootstrap.php' ) ) {
		require_once WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/bootstrap.php';
	}

	// Start up the WP testing environment.
	require_once $wp_tests_dir . '/includes/bootstrap.php';
}

bootstrap_integration_suite( get_wp_tests_dir() );
