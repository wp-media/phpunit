<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getFile
 * @group  VirtualFilesystemDirect
 */
class Test_Owner extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertNull( $this->filesystem->getFile( 'doesnotexist.html' ) );
		$this->assertNull( $this->filesystem->getFile( 'Tests/includes/index.php' ) );
		$this->assertNull( $this->filesystem->getFile( 'baz/bar/index.php' ) );
		$this->assertNull( $this->filesystem->getFile( 'public/Tests/includes/index.php' ) );
	}

	public function testShouldReturnOwnerUsernameWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->owner( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->owner( $file ) );
	}

	private function getExpected( $file ) {
		$id   = fileowner( $this->filesystem->getUrl( $file ) );
		$info = posix_getpwuid( $id );

		return $info['name'];
	}
}
