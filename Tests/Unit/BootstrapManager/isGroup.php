<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

use WPMedia\PHPUnit\BootstrapManager as Subject;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::isGroup
 * @group  BootstrapManager
 */
class Test_IsGroup extends TestCase {

	public function testShouldReturnTrueWhenGroupFlagMatchesRequestedName() {
		$this->setArgv( [ 'x', 'integration', '--group', 'AdminOnly' ] );

		$this->assertTrue( Subject::isGroup( 'AdminOnly' ) );
	}

	public function testShouldReturnFalseWhenGroupFlagNameDiffers() {
		$this->setArgv( [ 'x', 'integration', '--group', 'AdminOnly' ] );

		$this->assertFalse( Subject::isGroup( 'Multisite' ) );
	}

	public function testShouldReturnFalseWhenNoGroupFlagPresent() {
		$this->setArgv( [ 'x', 'integration' ] );

		$this->assertFalse( Subject::isGroup( 'AdminOnly' ) );
	}

	public function testShouldReturnFalseWhenGroupFlagHasNoNameAfterIt() {
		$this->setArgv( [ 'x', 'integration', '--group' ] );

		$this->assertFalse( Subject::isGroup( 'AdminOnly' ) );
	}
}
