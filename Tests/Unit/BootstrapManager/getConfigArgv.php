<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::getConfigArgv
 * @group  BootstrapManager
 */
class Test_GetConfigArgv extends TestCase {

	public function testShouldBuildTheBaseUnitScriptWhenOnlySuiteGiven() {
		$this->setArgv( [ 'vendor/bin/wpmedia-phpunit', 'unit' ] );

		$config = $this->invoke( 'getConfigArgv', [ 'unit' ] );

		$this->assertSame(
			[ 'vendor/bin/phpunit', '--testsuite', 'unit', '--colors=always', '--configuration' ],
			array_slice( $config, 0, 5 )
		);
		$this->assertStringEndsWith( 'phpunit.xml.dist', end( $config ) );
	}

	public function testShouldReturnAnEmptyScriptForAnUnknownSuite() {
		$this->setArgv( [ 'vendor/bin/wpmedia-phpunit', 'bogus' ] );

		$this->assertSame( [], $this->invoke( 'getConfigArgv', [ 'bogus' ] ) );
	}

	public function testShouldPassThroughExtraPhpunitArguments() {
		$this->setArgv( [ 'x', 'unit', '--filter', 'testSomething' ] );

		$config = $this->invoke( 'getConfigArgv', [ 'unit' ] );

		$this->assertContains( '--filter', $config );
		$this->assertContains( 'testSomething', $config );
	}

	public function testShouldStripConsumerPathAndRootDirArguments() {
		$this->setArgv(
			[ 'x', 'unit', 'path=Tests/Custom', 'WPMEDIA_PHPUNIT_ROOT_DIR=/srv/app', '--filter', 'foo' ]
		);

		$config = $this->invoke( 'getConfigArgv', [ 'unit' ] );

		// The wpmedia-phpunit-specific args are consumed here, not forwarded to PHPUnit...
		$this->assertNotContains( 'path=Tests/Custom', $config );
		$this->assertNotContains( 'WPMEDIA_PHPUNIT_ROOT_DIR=/srv/app', $config );

		// ...while genuine PHPUnit args still pass through.
		$this->assertContains( '--filter', $config );
		$this->assertContains( 'foo', $config );
	}
}
