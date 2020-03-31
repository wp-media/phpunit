<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getFile
 * @group  VirtualFilesystemDirect
 */
class Test_Size extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->size( 'doesnotexist.html' ) );
		$this->assertFalse( $this->filesystem->size( 'Tests/includes/index.php' ) );
		$this->assertFalse( $this->filesystem->size( 'baz/bar/index.php' ) );
		$this->assertFalse( $this->filesystem->size( 'public/Tests/includes/index.php' ) );
	}

	public function testShouldReturnBytesWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->size( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->size( $file ) );
	}

	private function getExpected( $file ) {
		return filesize( $this->filesystem->getUrl( $file ) );
	}
}
