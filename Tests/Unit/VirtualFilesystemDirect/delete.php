<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::delete
 * @group  VirtualFilesystemDirect
 */
class Test_Delete extends TestCase {

	function testShouldDeleteFileWhenExists() {
		$this->assertTrue( $this->filesystem->exists( 'baz/index.html' ) );
		$this->assertTrue( $this->filesystem->delete( 'baz/index.html' ) );
		$this->assertFalse( $this->filesystem->exists( 'baz/index.html' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/bootstrap.php' ) );
		$this->assertTrue( $this->filesystem->delete( 'Tests/Unit/bootstrap.php', false, 'f' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/bootstrap.php' ) );
	}

	function testShouldDeleteDirWhenExistsAndEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'Tests/includes/' ) );
		$this->assertTrue( $this->filesystem->delete( 'Tests/includes/' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/includes/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Integration/' ) );
		$this->assertTrue( $this->filesystem->delete( 'Tests/Integration/' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Integration/' ) );
	}

	function testShouldNotDeleteDirWhenNotEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'cache/baz/' ) );
		$this->assertFalse( $this->filesystem->delete( 'cache/baz/' ) );
		$this->assertTrue( $this->filesystem->exists( 'cache/baz/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
		$this->assertFalse( $this->filesystem->delete( 'Tests', false, 'd' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
	}

	function testShouldDeleteDirWhenNotEmpty() {
		$this->assertTrue( $this->filesystem->exists( 'baz/' ) );
		$this->assertTrue( $this->filesystem->delete( 'baz/', true ) );
		$this->assertFalse( $this->filesystem->exists( 'baz/' ) );

		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/' ) );
		$this->assertTrue( $this->filesystem->delete( 'Tests/Unit/', true ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/' ) );

		// Check that it removes the root directory.
		$this->assertTrue( $this->filesystem->exists( 'cache' ) );
		$this->assertTrue( $this->filesystem->rmdir( 'cache', true ) );
		$this->assertFalse( $this->filesystem->exists( 'cache' ) );
		$this->assertFalse( $this->filesystem->is_dir( 'cache' ) );
	}
}
