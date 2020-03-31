<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::mtime
 * @group  VirtualFilesystemDirect
 */
class Test_Mtime extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->mtime( 'doesnotexist.html' ) );
		$this->assertFalse( $this->filesystem->mtime( 'Tests/includes/index.php' ) );
		$this->assertFalse( $this->filesystem->mtime( 'baz/bar/index.php' ) );
		$this->assertFalse( $this->filesystem->mtime( 'public/Tests/includes/index.php' ) );
	}

	public function testShouldReturnModificationTimeWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->mtime( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->mtime( $file ) );
	}

	private function getExpected( $file ) {
		return filemtime( $this->filesystem->getUrl( $file ) );
	}
}
