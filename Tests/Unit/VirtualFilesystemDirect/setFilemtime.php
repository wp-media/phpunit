<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::setFilemtime
 * @group  VirtualFilesystemDirect
 */
class Test_SetFilemtime extends TestCase {

	public function testShouldChangeFilemtimeWhenStringGivenAndFileExists() {
		$expected = strtotime( '11 hours ago' );
		$this->assertSame( $expected, $this->filesystem->setFilemtime( 'Tests/Unit/SomeClass/getFile.php', '11 hours ago' ) );
		$this->assertSame( $expected, $this->filesystem->getFile( 'Tests/Unit/SomeClass/getFile.php' )->filemtime() );

		$expected = strtotime( '+1 week' );
		$this->assertSame( $expected, $this->filesystem->setFilemtime( 'public/Tests/Unit/SomeClass/getFile.php', '+1 week' ) );
		$this->assertSame( $expected, $this->filesystem->getFile( 'public/Tests/Unit/SomeClass/getFile.php' )->filemtime() );
	}

	public function testShouldChangeFilemtimeWhenTimeGivenAndFileExists() {
		$time = strtotime( 'now' );
		$this->assertSame( $time, $this->filesystem->setFilemtime( 'Tests/Unit/SomeClass/getFile.php', $time ) );
		$this->assertSame( $time, $this->filesystem->getFile( 'Tests/Unit/SomeClass/getFile.php' )->filemtime() );

		$time = strtotime( 'next Thursday' );
		$this->assertSame( $time, $this->filesystem->setFilemtime( 'public/Tests/Unit/SomeClass/getFile.php', $time ) );
		$this->assertSame( $time, $this->filesystem->getFile( 'public/Tests/Unit/SomeClass/getFile.php' )->filemtime() );
	}

	public function testShouldChangeFilemtimeWhenFileGiven() {
		$file = $this->filesystem->getFile( 'public/Tests/Unit/SomeClass/getFile.php' );

		$current_filemtime = $file->filemtime();
		$time              = strtotime( 'last Monday' );
		$this->assertSame( $time, $this->filesystem->setFilemtime( $file, $time ) );
		$this->assertNotSame( $current_filemtime, $file->filemtime() );
	}

	public function testShouldReturnNullWhenFileDoesNotExist() {
		$this->assertNull( $this->filesystem->setFilemtime( 'Tests/includes/index.php', 'now' ) );
		$this->assertNull( $this->filesystem->setFilemtime( 'public/Tests/index.php', 'now' ) );
	}
}
