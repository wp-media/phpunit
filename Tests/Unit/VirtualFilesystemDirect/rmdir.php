<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::rmdir
 * @group  VirtualFilesystemDirect
 */
class Test_Rmdir extends TestCase {

	function testShouldRemoveWhenDirExistsAndEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'Tests/includes/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'Tests/includes/' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/includes/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Integration/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'Tests/Integration/' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Integration/' ) );
	}

	function testShouldNotRemoveWhenDirNotEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'cache/baz/' ) );
		$this->assertFalse( $this->filesystem->rmdir( 'cache/baz/' ) );
		$this->assertTrue( $this->filesystem->exists( 'cache/baz/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
		$this->assertFalse( $this->filesystem->rmdir( 'Tests', false, 'd' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
	}

	function testShouldRemoveWhenRecursiveAndNotEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'baz/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'baz/', true ) );
		$this->assertFalse( $this->filesystem->exists( 'baz/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'Tests/Unit/', true ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/' ) );

		// Check that it removes the root directory.
		$this->assertTrue( $this->filesystem->exists( 'cache' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'cache', true ) );
		$this->assertFalse( $this->filesystem->exists( 'cache' ) );
		$this->assertFalse( $this->filesystem->is_dir( 'cache' ) );
	}
}
