<?php

namespace WPMedia\PHPUnit\Tests\Integration;

use WPMedia\PHPUnit\Integration\TestCase;

class Test_Bootstrap extends TestCase {

	function testShouldValidateConstantsValues() {
		$this->assertSame( dirname( dirname( __DIR__ ) ), WPMEDIA_PHPUNIT_ROOT_DIR );
		$this->assertNotSame( WPMEDIA_PHPUNIT_ROOT_DIR, ABSPATH );
		$this->assertSame( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'Tests/Integration', WPMEDIA_PHPUNIT_ROOT_TEST_DIR );
	}

	function testShouldReturnTrueWhenRootBootstrapFileIsInMemory() {
		$this->assertTrue( function_exists( __NAMESPACE__ . '\is_integration_test_bootstrap' ) );
		$this->assertTrue( is_integration_test_bootstrap() );
	}
}
