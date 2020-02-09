<?php

namespace WPMedia\PHPUnit\Tests\Unit;

/**
 * Simulates an optional bootstrap file in the root plugin, package, or library optional bootstrap file.
 *
 * Why? Used for testing that wpmedia-phpunit calls this file.
 *
 * @since 1.0.0
 *
 * @return bool true when this bootstrap.php was called.
 */
function is_unit_test_bootstrap() {
	return true;
}
