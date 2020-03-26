<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::rmdir
 * @group  VirtualFilesystemDirect
 */
class Test_Rmdir extends TestCase {

	public function testShouldRemoveWhenDirExistsAndEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'Tests/includes/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'Tests/includes/' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/includes/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Integration/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'Tests/Integration/' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Integration/' ) );
	}

	public function testShouldNotRemoveWhenDirNotEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'public/baz/' ) );
		$this->assertFalse( $this->filesystem->rmdir( 'public/baz/' ) );
		$this->assertTrue( $this->filesystem->exists( 'public/baz/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
		$this->assertFalse( $this->filesystem->rmdir( 'Tests', false, 'd' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
	}

	public function testShouldRemoveWhenRecursiveAndNotEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'baz/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'baz/', true ) );
		$this->assertFalse( $this->filesystem->exists( 'baz/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'Tests/Unit/', true ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/' ) );

		// Check that it removes the root directory.
		$this->assertTrue( $this->filesystem->exists( 'public' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'public', true ) );
		$this->assertFalse( $this->filesystem->exists( 'public' ) );
		$this->assertFalse( $this->filesystem->is_dir( 'public' ) );
	}
}
