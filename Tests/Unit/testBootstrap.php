<?php

namespace WPMedia\PHPUnit\Tests\Unit;

use WPMedia\PHPUnit\Unit\TestCase;
use function WPMedia\PHPUnit\check_readiness;

class Test_Bootstrap extends TestCase {

	function testShouldValidateConstantsValues() {
		$this->assertSame( dirname( dirname( __DIR__ ) ), WPMEDIA_PHPUNIT_ROOT_DIR );
		$this->assertTrue( ABSPATH );
		$this->assertSame( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'Tests/Unit', WPMEDIA_PHPUNIT_ROOT_TEST_DIR );
	}

	function testShouldReturnTrueWhenRootBootstrapFileIsInMemory() {
		$this->assertTrue( function_exists( __NAMESPACE__ . '\is_unit_test_bootstrap' ) );
		$this->assertTrue( is_unit_test_bootstrap() );
	}

	function testShouldAcceptMinimumSupportedPhpVersion() {
		$this->assertNull( check_readiness( '7.4.0' ) );
	}

	function testShouldRejectPhpVersionBelowSupportedMinimum() {
		$error = null;
		set_error_handler(
			function ( $errno, $errstr ) use ( &$error ) {
				$error = compact( 'errno', 'errstr' );
				return true;
			}
		);

		check_readiness( '7.3.99' );
		restore_error_handler();

		$this->assertSame( E_USER_ERROR, $error['errno'] );
		$this->assertSame( 'Test Suite requires PHP 7.4 or higher.', $error['errstr'] );
	}
}
