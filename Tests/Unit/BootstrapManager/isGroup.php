<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

use WPMedia\PHPUnit\BootstrapManager as Subject;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::isGroup
 * @group  BootstrapManager
 */
class Test_IsGroup extends TestCase {

	/**
	 * @dataProvider isGroupDataProvider
	 */
	public function testShouldDetectTheRequestedGroup( $argv, $group_name, $expected ) {
		$this->setArgv( $argv );

		$this->assertSame( $expected, Subject::isGroup( $group_name ) );
	}

	public function isGroupDataProvider() {
		return $this->getTestData( __DIR__, 'isGroup' );
	}
}
