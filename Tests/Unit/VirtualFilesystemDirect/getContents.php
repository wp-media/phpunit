<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::get_contents
 * @group  VirtualFilesystemDirect
 */
class Test_GetContents extends TestCase {

	public function testShouldReturnContentsWhenFileExists() {
		$this->assertSame( $this->structure['baz']['index.html'], $this->filesystem->get_contents( 'public/baz/index.html' ) );
		$this->assertSame( $this->structure['Tests']['Unit']['bootstrap.php'], $this->filesystem->get_contents( 'public/Tests/Unit/bootstrap.php' ) );
		$this->assertSame( $this->structure['Tests']['Unit']['SomeClass']['getFile.php'], $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' ) );
	}

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->get_contents( 'baz/invalid.html' ) );
		$this->assertFalse( $this->filesystem->get_contents( 'public/Tests/Unit/invalid.php' ) );
	}
}
