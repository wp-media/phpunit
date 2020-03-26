<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::exists
 * @group  VirtualFilesystemDirect
 */
class Test_Exists extends TestCase {

	public function testShouldReturnTrueWhenFileExists() {
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/bootstrap.php' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertTrue( $this->filesystem->exists( 'public/baz/index.html' ) );
	}

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/invalid.php' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Unit/SomeClass/invalid.php' ) );
	}

	public function testShouldReturnTrueWhenDirExists() {
		$this->assertTrue( $this->filesystem->exists( 'Tests' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/' ) );
		$this->assertTrue( $this->filesystem->exists( 'Tests/Unit/SomeClass' ) );
		$this->assertTrue( $this->filesystem->exists( 'baz' ) );
	}

	public function testShouldReturnFalseWhenDirDoesNotExist() {
		$this->assertFalse( $this->filesystem->exists( 'Tests/Rocket' ) );
		$this->assertFalse( $this->filesystem->exists( 'Tests/Invalid/' ) );
	}
}
