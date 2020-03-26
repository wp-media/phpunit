<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_readable
 * @group  VirtualFilesystemDirect
 */
class Test_IsReadable extends TestCase {

	public function testShouldReturnTrueWhenFileExistsAndIsReadable() {
		$this->assertTrue( $this->filesystem->is_readable( 'baz/index.html' ) );
		$this->assertTrue( $this->filesystem->is_readable( 'public/baz/index.html' ) );
	}

	public function testShouldReturnFalseWhenNoAccess() {
		$file = $this->filesystem->getFile( 'baz/index.html' );
		$file->chmod( 000 ); // Only root user.
		$this->assertFalse( $this->filesystem->is_readable( 'baz/index.html' ) );
	}

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_readable( 'doesnotexist.html' ) );
	}
}
