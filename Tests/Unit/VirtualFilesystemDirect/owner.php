<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::owner
 * @group  VirtualFilesystemDirect
 */
class Test_Owner extends TestCase {

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->owner( 'doesnotexist.html' ) );
		$this->assertFalse( $this->filesystem->owner( 'Tests/includes/index.php' ) );
		$this->assertFalse( $this->filesystem->owner( 'baz/bar/index.php' ) );
		$this->assertFalse( $this->filesystem->owner( 'public/Tests/includes/index.php' ) );
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
