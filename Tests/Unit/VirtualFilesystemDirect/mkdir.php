<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::mkdir
 * @group  VirtualFilesystemDirect
 */
class Test_Mkdir extends TestCase {

	function testShouldAddDirWhenDoesNot() {
		$dir = 'cache/newDir/';
		$this->assertFalse( $this->filesystem->exists( $dir ) );
		$this->assertTrue( $this->filesystem->mkdir( $dir ) );
		$this->assertTrue( $this->filesystem->exists( $dir ) );

		$dir = 'Tests/Integration/newDir';
		$this->assertFalse( $this->filesystem->exists( $dir ) );
		$this->assertTrue( $this->filesystem->mkdir( $dir ) );
		$this->assertTrue( $this->filesystem->exists( $dir ) );
	}

	function testShouldReturnFalseWhenDirExists() {
		$dir = 'cache/baz/';
		$this->assertTrue( $this->filesystem->exists( $dir ) );
		$this->assertFalse( $this->filesystem->mkdir( $dir ) );
		$this->assertTrue( $this->filesystem->exists( $dir ) );

		$dir = 'Tests/Unit/SomeClass';
		$this->assertTrue( $this->filesystem->exists( $dir ) );
		$this->assertFalse( $this->filesystem->mkdir( $dir ) );
		$this->assertTrue( $this->filesystem->exists( $dir ) );
	}

	function testShouldReturnFalseWhenMultipleNewDirs() {
		$dir = 'baz/newDir/newChildDir/newChildChildDir';
		$this->assertFalse( $this->filesystem->exists( $dir ) );
		$this->assertFalse( $this->filesystem->mkdir( $dir ) );
		$this->assertFalse( $this->filesystem->exists( 'cache/baz/newDir' ) );
		$this->assertFalse( $this->filesystem->exists( $dir ) );
	}

	function testShouldAddDirWithNewPermissionsWhenDirDoesNotAndModeGiven() {
		$dir = 'cache/newDir/';
		$this->assertFalse( $this->filesystem->exists( $dir ) );

		$this->assertTrue( $this->filesystem->mkdir( $dir, 0600 ) );
		$this->assertTrue( $this->filesystem->exists( $dir ) );

		$this->assertSame( 0600, $this->filesystem->getDir( $dir )->getPermissions() );
	}
}
