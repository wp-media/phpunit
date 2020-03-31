<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::chmod
 * @group  VirtualFilesystemDirect
 */
class Test_Getchmod extends TestCase {

	public function testShouldReturn0WhenFileDoesNotExist() {
		$this->assertSame( '0', $this->filesystem->getchmod( 'doesnotexist.html' ) );
		$this->assertSame( '0', $this->filesystem->getchmod( 'baz/doesnotexist.html' ) );
	}

	public function testShouldReturnModeWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->getchmod( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->getchmod( $file ) );
	}

	private function getExpected( $file ) {
		return substr( decoct( @fileperms( $this->filesystem->getUrl( $file ) ) ), -3 );
	}
}
