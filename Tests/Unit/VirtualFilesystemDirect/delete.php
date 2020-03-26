<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::delete
 * @group  VirtualFilesystemDirect
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteFileWhenExists() {
		$file = 'baz/index.html';
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->delete( $file ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );

		// Check with the Url version.
		$file = $this->filesystem->getUrl( 'Tests/Unit/bootstrap.php' );
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->delete( $file, false, 'f' ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );
	}

	public function testShouldDeleteDirWhenExistsAndEmpty() {
		$file = 'Tests/includes/';
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->delete( $file ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );

		// Check with the Url version.
		$file = $this->filesystem->getUrl( 'Tests/Integration/' );
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->delete( $file ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );
	}

	public function testShouldNotDeleteDirWhenNotEmpty() {
		$file = 'public/baz/';
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertFalse( $this->filesystem->delete( $file ) );
		$this->assertTrue( $this->filesystem->exists( $file ) );

		// Check with the Url version.
		$file = $this->filesystem->getUrl( 'Tests' );
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertFalse( $this->filesystem->delete( $file, false, 'd' ) );
		$this->assertTrue( $this->filesystem->exists( $file ) );
	}

	public function testShouldDeleteDirWhenNotEmpty() {
		$file = 'baz/';
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->delete( $file, true ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );

		// Check with the Url version.
		$file = $this->filesystem->getUrl( 'Tests/Unit/' );
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->delete( $file, true ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );

		// Check that it removes the root directory.
		$file = 'public';
		$this->assertTrue( $this->filesystem->exists( $file ) );
		$this->assertTrue( $this->filesystem->rmdir( $file, true ) );
		$this->assertFalse( $this->filesystem->exists( $file ) );
		$this->assertFalse( $this->filesystem->is_dir( $file ) );
	}
}
