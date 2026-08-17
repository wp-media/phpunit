<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::removeCliArg
 * @group  BootstrapManager
 */
class Test_RemoveCliArg extends TestCase {

	/**
	 * @dataProvider removeCliArgDataProvider
	 */
	public function testShouldRemoveTheMatchingArg( $argv, $key, $expected ) {
		$this->setArgv( $argv );

		$this->invoke( 'removeCliArg', [ $key ] );

		$this->assertSame( $expected, $_SERVER['argv'] );
	}

	public function removeCliArgDataProvider() {
		return $this->getTestData( __DIR__, 'removeCliArg' );
	}
}
