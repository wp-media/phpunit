<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getFile
 * @group  VirtualFilesystemDirect
 */
class Test_Group extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertNull( $this->filesystem->getFile( 'doesnotexist.html' ) );
		$this->assertNull( $this->filesystem->getFile( 'Tests/includes/index.php' ) );
		$this->assertNull( $this->filesystem->getFile( 'baz/bar/index.php' ) );
		$this->assertNull( $this->filesystem->getFile( 'public/Tests/includes/index.php' ) );
	}

	public function testShouldReturnGroupNameWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->group( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->group( $file ) );
	}

	private function getExpected( $file ) {
		$id = filegroup( $this->filesystem->getUrl( 'Tests/Unit/bootstrap.php' ) );
		$info = posix_getgrgid( $id );
		return $info['name'];
	}
}
