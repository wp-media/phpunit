<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getFile
 * @group  VirtualFilesystemDirect
 */
class Test_Group extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->group( 'doesnotexist.html' ) );
		$this->assertFalse( $this->filesystem->group( 'Tests/includes/index.php' ) );
		$this->assertFalse( $this->filesystem->group( 'baz/bar/index.php' ) );
		$this->assertFalse( $this->filesystem->group( 'public/Tests/includes/index.php' ) );
	}

	public function testShouldReturnGroupNameWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->group( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->group( $file ) );
	}

	private function getExpected( $file ) {
		$id   = filegroup( $this->filesystem->getUrl( $file ) );
		$info = posix_getgrgid( $id );

		return $info['name'];
	}
}
