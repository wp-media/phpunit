<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_file
 * @group  VirtualFilesystemDirect
 */
class Test_IsFile extends TestCase {

	public function testShouldReturnTrueWhenFileExists() {
		$this->assertTrue( $this->filesystem->is_file( 'Tests/Unit/bootstrap.php' ) );
		$this->assertTrue( $this->filesystem->is_file( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertTrue( $this->filesystem->is_file( 'public/baz/index.html' ) );
	}

	public function testShouldReturnFalseWhenDir() {
		$this->assertFalse( $this->filesystem->is_file( 'Tests' ) );
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/' ) );
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/SomeClass' ) );
		$this->assertFalse( $this->filesystem->is_file( 'baz' ) );
	}

	public function testShouldReturnTrueWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/index.php' ) );
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/SomeClass/index.php' ) );
	}
}
