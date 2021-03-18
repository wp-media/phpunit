<?php

namespace WPMedia\PHPUnit\Tests\Unit;

use WPMedia\PHPUnit\Unit\TestCase;

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
}
