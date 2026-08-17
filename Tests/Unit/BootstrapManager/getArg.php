<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::getArg
 * @group  BootstrapManager
 */
class Test_GetArg extends TestCase {

	/**
	 * @dataProvider getArgDataProvider
	 */
	public function testShouldReturnTheParsedArg( $argv, $key, $expected ) {
		$this->setArgv( $argv );

		$this->assertSame( $expected, $this->invoke( 'getArg', [ $key ] ) );
	}

	public function getArgDataProvider() {
		return $this->getTestData( __DIR__, 'getArg' );
	}
}
