<?php
/**
 * Initializes the wp-media/phpunit handler, which then calls the unit test suite.
 */

if ( ! defined( 'WPMEDIA_PHPUNIT_ROOT_DIR' ) ) {
	define( 'WPMEDIA_PHPUNIT_ROOT_DIR', dirname( dirname( __DIR__ ) ) );
}
if ( ! defined( 'WPMEDIA_PHPUNIT_ROOT_TEST_DIR' ) ) {
	define( 'WPMEDIA_PHPUNIT_ROOT_TEST_DIR', __DIR__ );
}

require_once WPMEDIA_PHPUNIT_ROOT_DIR . '/src/Integration/bootstrap.php';