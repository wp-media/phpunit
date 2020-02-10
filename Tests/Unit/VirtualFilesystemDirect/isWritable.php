<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::is_writable
 * @group  VirtualFilesystemDirect
 */
class Test_IsWritable extends TestCase {
	protected $structure = [ 'index.html' => 'Lorem ipsum dolor sit amet.' 	];


	function testShouldReturnTrueWhenFileExistsAndIsReadable() {
		$this->assertTrue( $this->filesystem->is_writable( 'index.html' ) );
		$this->assertTrue( $this->filesystem->is_writable( 'cache/index.html' ) );
	}

	function testShouldReturnFalseWhenNoAccess() {
		$file = $this->filesystem->getFile( 'index.html' ) ;
		$file->chmod( 000 ); // Only root user.
		$this->assertFalse( $this->filesystem->is_writable( 'index.html' ) );
	}

	function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->is_writable( 'doesnotexist.html' ) );
	}
}
