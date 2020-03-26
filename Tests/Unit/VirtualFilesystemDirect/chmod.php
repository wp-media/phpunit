<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::chmod
 * @group  VirtualFilesystemDirect
 */
class Test_Chmod extends TestCase {

	public function testShouldChangeFileModeWhenFileExists() {
		$file = $this->filesystem->getFile( 'baz/index.html' ) ;
		$original = $file->getPermissions();
		$this->assertTrue( $this->filesystem->chmod( 'baz/index.html', 0600 ) );
		$this->assertNotEquals( $original, $file->getPermissions() );
		$this->assertSame( 0600, $file->getPermissions() );
		$this->assertTrue( $this->filesystem->chmod( 'baz/index.html', 0755 ) );
		$this->assertSame( 0755, $file->getPermissions() );

		$file = $this->filesystem->getFile( 'Tests/Unit/bootstrap.php' ) ;
		$original = $file->getPermissions();
		$this->assertTrue( $this->filesystem->chmod( 'Tests/Unit/bootstrap.php', 0600 ) );
		$this->assertNotEquals( $original, $file->getPermissions() );
		$this->assertSame( 0600, $file->getPermissions() );
		$this->assertTrue( $this->filesystem->chmod( 'Tests/Unit/bootstrap.php', 0644 ) );
		$this->assertSame( 0644, $file->getPermissions() );
	}

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->chmod( 'doesnotexist.html', 0600 ) );
		$this->assertFalse( $this->filesystem->chmod( 'baz/doesnotexist.html', 0755 ) );
	}

	public function testShouldChangeDirModeWhenExists() {
		$dir = $this->filesystem->getDir( 'Tests/Unit' ) ;
		$original = $dir->getPermissions();
		$this->assertTrue( $this->filesystem->chmod( 'Tests/Unit', 0600 ) );
		$this->assertNotEquals( $original, $dir->getPermissions() );
		$this->assertSame( 0600, $dir->getPermissions() );
		$this->assertTrue( $this->filesystem->chmod( 'Tests/Unit', 0755 ) );
		$this->assertSame( 0755, $dir->getPermissions() );

		$dir = $this->filesystem->getDir( 'public/baz/' ) ;
		$original = $dir->getPermissions();
		$this->assertTrue( $this->filesystem->chmod( 'public/baz/', 0600 ) );
		$this->assertNotEquals( $original, $dir->getPermissions() );
		$this->assertSame( 0600, $dir->getPermissions() );
		$this->assertTrue( $this->filesystem->chmod( 'public/baz/', 0644 ) );
		$this->assertSame( 0644, $dir->getPermissions() );
	}

	public function testShouldReturnFalseWhenDirDoesNotExist() {
		$this->assertFalse( $this->filesystem->chmod( 'public/Invalid/', 0600 ) );
		$this->assertFalse( $this->filesystem->chmod( 'public/Tests/Unit/Invalid', 0755 ) );
	}
}
