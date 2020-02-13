<?php

namespace WPMedia\PHPUnit\Tests\Integration;

use WPMedia\PHPUnit\BootstrapManager;
use WPMedia\PHPUnit\Integration\TestCase;

/**
 * @group AdminOnly
 */
class Test_AdminOnly extends TestCase {

	function testShouldReturnTrueWhenAdminOnlyGroupExists() {
		$this->assertTrue( BootstrapManager::isGroup( 'AdminOnly') );
	}

	function testShouldReturnFalseWhenGroupNotRequested() {
		$this->assertFalse( BootstrapManager::isGroup( 'Baz') );
	}

	function testShouldCheckWPAdminIsSet() {
		$this->assertTrue( defined( 'WP_ADMIN' ) );
		$this->assertTrue( WP_ADMIN );
	}
}
