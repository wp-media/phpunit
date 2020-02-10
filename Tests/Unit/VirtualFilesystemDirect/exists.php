<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::exists
 * @group  VirtualFilesystemDirect
 */
class Test_Exists extends TestCase {

	function testShouldReturnTrueWhenFileExists() {
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/bootstrap.php' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertTrue( $this->filesystem->exists( 'cache/baz/index.html' ) );
	}

	function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/invalid.php' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/SomeClass/invalid.php' ) );
	}

	function testShouldReturnTrueWhenDirExists() {
		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/SomeClass' ) );
		$this->assertTrue( $this->filesystem->exists( 'baz' ) );
	}

	function testShouldReturnFalseWhenDirDoesNotExist() {
		$this->assertFalse( $this->filesystem->exists( 'Tests/Rocket' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Invalid/' ) );
	}
}
