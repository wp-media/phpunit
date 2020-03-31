<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::chmod
 * @group  VirtualFilesystemDirect
 */
class Test_Gethchmod extends TestCase {

	public function testShouldReturn0WhenFileDoesNotExist() {
		$this->assertSame( 'u---------', $this->filesystem->gethchmod( 'doesnotexist.html' ) );
		$this->assertSame( 'u---------', $this->filesystem->gethchmod( 'baz/doesnotexist.html' ) );
	}

	public function testShouldReturnModeWhenFileExists() {
		$file = 'Tests/Unit/bootstrap.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->gethchmod( $file ) );
		$file = 'Tests/Unit/SomeClass/getFile.php';
		$this->assertSame( $this->getExpected( $file ), $this->filesystem->gethchmod( $file ) );
	}

	private function getExpected( $file ) {
		return $this->wpGethchmod( $this->filesystem->getUrl( $file ) );
	}

	/**
	 * Copied directly from WP_Filesystem_Direct.
	 */
	private function wpGethchmod( $file ) {
		$perms = intval( $this->getchmod( $file ), 8 );
		if ( ( $perms & 0xC000 ) == 0xC000 ) { // Socket
			$info = 's';
		} elseif ( ( $perms & 0xA000 ) == 0xA000 ) { // Symbolic Link
			$info = 'l';
		} elseif ( ( $perms & 0x8000 ) == 0x8000 ) { // Regular
			$info = '-';
		} elseif ( ( $perms & 0x6000 ) == 0x6000 ) { // Block special
			$info = 'b';
		} elseif ( ( $perms & 0x4000 ) == 0x4000 ) { // Directory
			$info = 'd';
		} elseif ( ( $perms & 0x2000 ) == 0x2000 ) { // Character special
			$info = 'c';
		} elseif ( ( $perms & 0x1000 ) == 0x1000 ) { // FIFO pipe
			$info = 'p';
		} else { // Unknown
			$info = 'u';
		}

		// Owner
		$info .= ( ( $perms & 0x0100 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0080 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0040 ) ?
			( ( $perms & 0x0800 ) ? 's' : 'x' ) :
			( ( $perms & 0x0800 ) ? 'S' : '-' ) );

		// Group
		$info .= ( ( $perms & 0x0020 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0010 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0008 ) ?
			( ( $perms & 0x0400 ) ? 's' : 'x' ) :
			( ( $perms & 0x0400 ) ? 'S' : '-' ) );

		// World
		$info .= ( ( $perms & 0x0004 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0002 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0001 ) ?
			( ( $perms & 0x0200 ) ? 't' : 'x' ) :
			( ( $perms & 0x0200 ) ? 'T' : '-' ) );
		return $info;
	}

	/**
	 * Copied directly from WP_Filesystem_Direct.
	 */
	private function getchmod( $file ) {
		return substr( decoct( @fileperms( $file ) ), -3 );
	}
}
