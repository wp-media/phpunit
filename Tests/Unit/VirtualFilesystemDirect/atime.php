<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::atime
 * @group  VirtualFilesystemDirect
 */
class Test_Atime extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->atime( 'doesnotexist.html' ) );
		$this->assertFalse( $this->filesystem->atime( 'Tests/includes/index.php' ) );
		$this->assertFalse( $this->filesystem->atime( 'baz/bar/index.php' ) );
		$this->assertFalse( $this->filesystem->atime( 'public/Tests/includes/index.php' ) );
	}

	public function testShouldReturnAccessTimeWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->atime( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->atime( $file ) );
	}

	private function getExpected( $file ) {
		return fileatime( $this->filesystem->getUrl( $file ) );
	}
}
