<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_readable
 * @group  VirtualFilesystemDirect
 */
class Test_IsReadable extends TestCase {
	protected $structure = [ 'index.html' => 'Lorem ipsum dolor sit amet.' 	];

	function testShouldReturnTrueWhenFileExistsAndIsReadable() {
		$this->assertTrue( $this->filesystem->is_readable( 'index.html' ) );
		$this->assertTrue( $this->filesystem->is_readable( 'cache/index.html' ) );
	}

	function testShouldReturnFalseWhenNoAccess() {
		$file = $this->filesystem->getFile( 'index.html' ) ;
		$file->chmod( 000 ); // Only root user.
		$this->assertFalse( $this->filesystem->is_readable( 'index.html' ) );
	}

	function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_readable( 'doesnotexist.html' ) );
	}
}
