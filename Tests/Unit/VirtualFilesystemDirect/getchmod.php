<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getchmod
 * @group  VirtualFilesystemDirect
 */
class Test_Getchmod extends TestCase {

	public function testShouldReturn0WhenFileDoesNotExist() {
		$this->assertSame( '0', $this->filesystem->getchmod( 'doesnotexist.html' ) );
		$this->assertSame( '0', $this->filesystem->getchmod( 'baz/doesnotexist.html' ) );
	}

	public function testShouldReturnModeWhenFileExists() {
		$this->assertSame( '666', $this->filesystem->getchmod( 'Tests/Unit/bootstrap.php' ) );
		$this->assertSame( '666', $this->filesystem->getchmod( 'Tests/Unit/SomeClass/getFile.php' ) );
	}
}
