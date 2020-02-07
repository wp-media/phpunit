<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getFile
 * @group  VirtualFilesystemDirect
 */
class Test_GetFile extends TestCase {

	function testShouldReturnInstanceOfFileWhenFileExists() {
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamFile', $this->filesystem->getFile( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamFile', $this->filesystem->getFile( 'cache/Tests/TestCase.php' ) );
	}

	function testShouldReturnNullWhenFileDoesNotExist() {
		$this->assertNull( $this->filesystem->getFile( 'doesnotexist.html' ) );
		$this->assertNull( $this->filesystem->getFile( 'Tests/includes/index.php' ) );
		$this->assertNull( $this->filesystem->getFile( 'baz/bar/index.php' ) );
		$this->assertNull( $this->filesystem->getFile( 'cache/Tests/includes/index.php' ) );
	}
}
