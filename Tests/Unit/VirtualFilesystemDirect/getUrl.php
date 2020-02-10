<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getUrl
 * @group  VirtualFilesystemDirect
 */
class Test_GetUrl extends TestCase {

	function testShouldReturnURLFileWhenFileExists() {
		$this->assertSame( 'vfs://cache/Tests/Unit/SomeClass/getFile.php', $this->filesystem->getUrl( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertSame( 'vfs://cache/Tests/TestCase.php', $this->filesystem->getUrl( 'cache/Tests/TestCase.php' ) );
	}

	function testShouldReturnUrlWhenFileDoesNotExist() {
		$this->assertSame( 'vfs://cache/doesnotexist.html', $this->filesystem->getUrl( 'doesnotexist.html' ) );
		$this->assertSame( 'vfs://cache/Tests/includes/index.php', $this->filesystem->getUrl( 'Tests/includes/index.php' ) );
	}
}
