<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::touch
 * @group  VirtualFilesystemDirect
 */
class Test_Touch extends TestCase {

	public function testShouldChangeModifiedAndAccessTimesWhenFileExists() {
		$path = 'baz/index.html';
		$this->assertTrue( $this->filesystem->touch( $path, 303, 313 ) );
		$file = $this->filesystem->getFile( $path );
		$this->assertEquals( 303, $file->filemtime() );
		$this->assertEquals( 313, $file->fileatime() );

		$path = 'public/Tests/Unit/SomeClass/getFile.php';
		$this->assertTrue( $this->filesystem->touch( $path, 603, 613 ) );
		$file = $this->filesystem->getFile( $path );
		$this->assertEquals( 603, $file->filemtime() );
		$this->assertEquals( 613, $file->fileatime() );
	}

	public function testShouldChangeModifiedAndAccessTimesWhenFileExistsAndTimesNotGiven() {
		$time = strtotime( '11 hours ago' );

		foreach ( [ 'baz/index.html', 'public/Tests/Unit/SomeClass/getFile.php' ] as $path ) {
			$file = $this->filesystem->getFile( $path );
			$file->lastModified( $time );
			$file->lastAccessed( $time );
			$this->assertTrue( $this->filesystem->touch( $path ) );
			$this->assertNotEquals( $time, $file->filemtime() );
			$this->assertNotEquals( $time, $file->fileatime() );
		}
	}

	public function testShouldCreateFileWhenDoesNotExist() {
		foreach ( [ 'baz/newfile.html', 'public/Tests/Unit/SomeClass/newfile.php' ] as $path ) {
			$this->assertTrue( $this->filesystem->touch( $path ) );
			$this->assertTrue( $this->filesystem->exists( $path ) );
			$this->assertTrue( $this->filesystem->is_file( $path ) );
		}

		// Check when giving time(s).
		$path = 'public/Tests/Unit/newfile.php';
		$time = strtotime( '11 hours ago' );
		$this->assertTrue( $this->filesystem->touch( $path, $time, $time ) );
		$this->assertTrue( $this->filesystem->exists( $path ) );
		$this->assertTrue( $this->filesystem->is_file( $path ) );
		$file = $this->filesystem->getFile( $path );
		$this->assertEquals( $time, $file->filemtime() );
		$this->assertEquals( $time, $file->fileatime() );
	}
}
