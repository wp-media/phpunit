<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getUrl
 * @group  VirtualFilesystemDirect
 */
class Test_GetUrl extends TestCase {

	public function testShouldReturnURLFileWhenFileExists() {
		$this->assertSame( 'vfs://public/Tests/Unit/SomeClass/getFile.php', $this->filesystem->getUrl( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertSame( 'vfs://public/Tests/TestCase.php', $this->filesystem->getUrl( 'public/Tests/TestCase.php' ) );
	}

	public function testShouldReturnUrlWhenFileDoesNotExist() {
		$this->assertSame( 'vfs://public/doesnotexist.html', $this->filesystem->getUrl( 'doesnotexist.html' ) );
		$this->assertSame( 'vfs://public/Tests/includes/index.php', $this->filesystem->getUrl( 'Tests/includes/index.php' ) );
	}
}
