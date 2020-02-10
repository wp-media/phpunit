<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_file
 * @group  VirtualFilesystemDirect
 */
class Test_IsFile extends TestCase {

	function testShouldReturnTrueWhenFileExists() {
		$this->assertTrue( $this->filesystem->is_file( 'Tests/Unit/bootstrap.php' ) );
		$this->assertTrue( $this->filesystem->is_file( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertTrue( $this->filesystem->is_file( 'cache/baz/index.html' ) );
	}

	function testShouldReturnFalseWhenDir() {
		$this->assertFalse( $this->filesystem->is_file( 'Tests' ) );
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/' ) );
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/SomeClass' ) );
		$this->assertFalse( $this->filesystem->is_file( 'baz' ) );
	}

	function testShouldReturnTrueWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/index.php' ) );
		$this->assertFalse( $this->filesystem->is_file( 'Tests/Unit/SomeClass/index.php' ) );
	}
}
