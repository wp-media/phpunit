<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::removeCliArg
 * @group  BootstrapManager
 */
class Test_RemoveCliArg extends TestCase {

	public function testShouldRemoveMatchingArgFromArgv() {
		$this->setArgv( [ 'x', 'unit', 'path=Tests/Foo', '--filter', 'bar' ] );

		$this->invoke( 'removeCliArg', [ 'path' ] );

		// The element is unset in place; remaining keys are left untouched (not reindexed).
		$this->assertArrayNotHasKey( 2, $_SERVER['argv'] );
		$this->assertSame(
			[ 0 => 'x', 1 => 'unit', 3 => '--filter', 4 => 'bar' ],
			$_SERVER['argv']
		);
	}

	public function testShouldLeaveArgvUnchangedWhenArgAbsent() {
		$this->setArgv( [ 'x', 'unit', '--filter', 'bar' ] );

		$this->invoke( 'removeCliArg', [ 'path' ] );

		$this->assertSame( [ 'x', 'unit', '--filter', 'bar' ], $_SERVER['argv'] );
	}
}
