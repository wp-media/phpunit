<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_writable
 * @group  VirtualFilesystemDirect
 */
class Test_IsWritable extends TestCase {

	public function testShouldReturnTrueWhenFileExistsAndIsReadable() {
		$this->assertTrue( $this->filesystem->is_writable( 'baz/index.html' ) );
		$this->assertTrue( $this->filesystem->is_writable( 'public/baz/index.html' ) );
	}

	public function testShouldReturnFalseWhenNoAccess() {
		$file = $this->filesystem->getFile( 'baz/index.html' );
		$file->chmod( 000 ); // Only root user.
		$this->assertFalse( $this->filesystem->is_writable( 'baz/index.html' ) );
	}

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_writable( 'doesnotexist.html' ) );
	}
}
