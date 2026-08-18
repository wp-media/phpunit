<?php

namespace WPMedia\PHPUnit\Unit;

use WPMedia\PHPUnit\BootstrapManager;
use function WPMedia\PHPUnit\init_test_suite;

// Excludes this package's self-executing entry points from PHPUnit's process-isolation
// re-include mechanism. Must run here (loaded via PHPUnit's own bootstrap= configuration),
// not from BootstrapManager::runTestSuite(): vendor/bin/phpunit overwrites the isolation
// exclude list the moment it is required, wiping any earlier registration. See issue #51.
BootstrapManager::registerIsolationExcludeList();

require_once WPMEDIA_PHPUNIT_ROOT_DIR . '/vendor/yoast/wp-test-utils/src/BrainMonkey/bootstrap.php';
require_once dirname( dirname( __FILE__ ) ) . '/bootstrap-functions.php';
init_test_suite();

// Bootstrap the wp-media/phpunit-{add-on}.
if (
	defined( 'WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR' )
	&&
	is_readable( WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR . '/bootstrap.php' )
) {
	require_once WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR . '/bootstrap.php';
}

// Bootstrap the plugin.
if ( is_readable( WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/bootstrap.php' ) ) {
	require_once WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/bootstrap.php';
}
