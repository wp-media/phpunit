<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getDir
 * @group  VirtualFilesystemDirect
 */
class Test_GetDir extends TestCase {

	public function testShouldReturnInstanceWhenDirExists() {
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamDirectory', $this->filesystem->getDir( 'Tests/Unit/SomeClass' ) );
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamDirectory', $this->filesystem->getDir( 'public/Tests/' ) );
		$this->assertInstanceOf( 'org\bovigo\vfs\vfsStreamDirectory', $this->filesystem->getDir( 'baz' ) );
	}

	public function testShouldReturnNullWhenDirDoesNotExist() {
		$this->assertNull( $this->filesystem->getDir( 'public/Invalid/' ) );
		$this->assertNull( $this->filesystem->getDir( 'Invalid/' ) );
		$this->assertNull( $this->filesystem->getDir( 'public/Tests/Unit/Invalid' ) );
	}
}
