<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::getArg
 * @group  BootstrapManager
 */
class Test_GetArg extends TestCase {

	public function testShouldReturnIndexAndValueWhenKeyValueArgExists() {
		$this->setArgv( [ 'vendor/bin/wpmedia-phpunit', 'unit', 'path=Tests/Foo' ] );

		$this->assertSame(
			[ 'index' => 2, 'path' => 'Tests/Foo' ],
			$this->invoke( 'getArg', [ 'path' ] )
		);
	}

	public function testShouldReturnRootDirArgWithItsAbsolutePathValue() {
		$this->setArgv( [ 'x', 'integration', 'WPMEDIA_PHPUNIT_ROOT_DIR=/var/www/plugin' ] );

		$this->assertSame(
			[ 'index' => 2, 'WPMEDIA_PHPUNIT_ROOT_DIR' => '/var/www/plugin' ],
			$this->invoke( 'getArg', [ 'WPMEDIA_PHPUNIT_ROOT_DIR' ] )
		);
	}

	public function testShouldReturnFlagAsItsOwnValueWhenNoEqualsSign() {
		$this->setArgv( [ 'x', 'integration', '--group', 'AdminOnly' ] );

		// There is no `--group=` prefix to strip, so the value is the flag itself.
		$this->assertSame(
			[ 'index' => 2, '--group' => '--group' ],
			$this->invoke( 'getArg', [ '--group' ] )
		);
	}

	public function testShouldReturnFalseWhenKeyIsAbsent() {
		$this->setArgv( [ 'x', 'unit' ] );

		$this->assertFalse( $this->invoke( 'getArg', [ 'path' ] ) );
	}
}
