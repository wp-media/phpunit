<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_dir
 * @group  VirtualFilesystemDirect
 */
class Test_IsDir extends TestCase {

	function testShouldReturnTrueWhenDir() {
		$this->assertTrue( $this->filesystem->is_dir( 'Tests' ) );
		$this->assertTrue( $this->filesystem->is_dir( 'Tests/Unit/' ) );
		$this->assertTrue( $this->filesystem->is_dir( 'Tests/Unit/SomeClass' ) );
		$this->assertTrue( $this->filesystem->is_dir( 'baz' ) );
	}

	function testShouldReturnFalseWhenFileGiven() {
		$this->assertFalse( $this->filesystem->is_dir( 'Tests/Unit/bootstrap.php' ) );
		$this->assertFalse( $this->filesystem->is_dir( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertFalse( $this->filesystem->is_dir( 'cache/baz/index.html' ) );
	}

	function testShouldReturnFalseWhenDirDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_dir( 'Tests/Rocket' ) );
		$this->assertFalse( $this->filesystem->is_dir( 'Tests/Invalid/' ) );
	}
}
