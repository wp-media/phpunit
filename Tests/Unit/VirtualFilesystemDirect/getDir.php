<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getDir
 * @group  VirtualFilesystemDirect
 */
class Test_GetDir extends TestCase {

	function testShouldReturnInstanceWhenDirExists() {
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamDirectory', $this->filesystem->getDir( 'Tests/Unit/SomeClass' ) );
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamDirectory', $this->filesystem->getDir( 'cache/Tests/' ) );
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamDirectory', $this->filesystem->getDir( 'baz' ) );
	}

	function testShouldReturnNullWhenDirDoesNotExist() {
		$this->assertNull( $this->filesystem->getDir( 'cache/Invalid/' ) );
		$this->assertNull( $this->filesystem->getDir( 'Invalid/' ) );
		$this->assertNull( $this->filesystem->getDir( 'cache/Tests/Unit/Invalid' ) );
	}
}
