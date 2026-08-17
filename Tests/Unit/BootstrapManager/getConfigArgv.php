<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::getConfigArgv
 * @group  BootstrapManager
 */
class Test_GetConfigArgv extends TestCase {

	/**
	 * @dataProvider getConfigArgvDataProvider
	 */
	public function testShouldBuildThePhpunitArgv( $argv, $test, $expected ) {
		// getConfigArgv() resolves the config path from WPMEDIA_PHPUNIT_ROOT_TEST_DIR at runtime;
		// swap the fixture token for that path before asserting.
		$config_path = WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/phpunit.xml.dist';
		$expected    = array_map(
			static function ( $arg ) use ( $config_path ) {
				return '{{unit_config}}' === $arg ? $config_path : $arg;
			},
			(array) $expected
		);

		$this->setArgv( $argv );

		$this->assertSame( $expected, $this->invoke( 'getConfigArgv', [ $test ] ) );
	}

	public function getConfigArgvDataProvider() {
		return $this->getTestData( __DIR__, 'getConfigArgv' );
	}
}
