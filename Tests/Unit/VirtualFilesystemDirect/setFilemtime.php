<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::setFilemtime
 * @group  VirtualFilesystemDirect
 */
class Test_SetFilemtime extends TestCase {

	function testShouldChangeFilemtimeWhenStringGivenAndFileExists() {
		$expected = strtotime( '11 hours ago' );
		$this->assertSame( $expected, $this->filesystem->setFilemtime( 'Tests/Unit/SomeClass/getFile.php', '11 hours ago' ) );
		$this->assertSame( $expected, $this->filesystem->getFile( 'Tests/Unit/SomeClass/getFile.php' )->filemtime() );

		$expected = strtotime( '+1 week' );
		$this->assertSame( $expected, $this->filesystem->setFilemtime( 'cache/Tests/Unit/SomeClass/getFile.php', '+1 week' ) );
		$this->assertSame( $expected, $this->filesystem->getFile( 'cache/Tests/Unit/SomeClass/getFile.php' )->filemtime() );
	}

	function testShouldChangeFilemtimeWhenTimeGivenAndFileExists() {
		$time = strtotime( 'now' );
		$this->assertSame( $time, $this->filesystem->setFilemtime( 'Tests/Unit/SomeClass/getFile.php', $time ) );
		$this->assertSame( $time, $this->filesystem->getFile( 'Tests/Unit/SomeClass/getFile.php' )->filemtime() );

		$time = strtotime( 'next Thursday' );
		$this->assertSame( $time, $this->filesystem->setFilemtime( 'cache/Tests/Unit/SomeClass/getFile.php', $time ) );
		$this->assertSame( $time, $this->filesystem->getFile( 'cache/Tests/Unit/SomeClass/getFile.php' )->filemtime() );
	}


	function testShouldChangeFilemtimeWhenFileGiven() {
		$file = $this->filesystem->getFile( 'cache/Tests/Unit/SomeClass/getFile.php' );

		$current_filemtime = $file->filemtime();
		$time              = strtotime( 'last Monday' );
		$this->assertSame( $time, $this->filesystem->setFilemtime( $file, $time ) );
		$this->assertNotSame( $current_filemtime, $file->filemtime() );
	}

	function testShouldReturnNullWhenFileDoesNotExist() {
		$this->assertNull( $this->filesystem->setFilemtime( 'Tests/includes/index.php', 'now' ) );
		$this->assertNull( $this->filesystem->setFilemtime( 'cache/Tests/index.php', 'now' ) );
	}
}
