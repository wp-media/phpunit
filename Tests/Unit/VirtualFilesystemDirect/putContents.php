<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::put_contents
 * @group  VirtualFilesystemDirect
 */
class Test_PutContents extends TestCase {

	public function testShouldPutContentsWhenFileExists() {
		$original = $this->filesystem->get_contents( 'public/baz/index.html' );
		$content  = 'New contents';
		$this->assertTrue( $this->filesystem->put_contents( 'public/baz/index.html', $content ) );
		$this->assertNotSame( $original, $this->filesystem->get_contents( 'public/baz/index.html' ) );
		$this->assertSame( $content, $this->filesystem->get_contents( 'public/baz/index.html' ) );

		$original = $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' );
		$content  = 'New contents';
		$this->assertTrue( $this->filesystem->put_contents( 'Tests/Unit/SomeClass/getFile.php', $content ) );
		$this->assertNotSame( $original, $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertSame( $content, $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' ) );
	}

	public function testShouldCreateFileAndPutContentsWhenFileDoesNotExist() {
		$data = [
			'baz/newfile.html' => 'Lorem ipsum dolor sit amet',
			'public/Tests/Unit/SomeClass/putContents.php' => 'Praesent a nibh in nulla dapibus gravida.',
		];
		foreach( $data as $filename => $content ) {
			$this->assertFalse( $this->filesystem->exists( $filename ) );
			$this->assertTrue( $this->filesystem->put_contents( $filename, $content ) );
			$this->assertTrue( $this->filesystem->exists( $filename ) );
			$this->assertSame( $content, $this->filesystem->get_contents( $filename ) );
		}

		// Test with fopen() just to be sure.
		$filename = 'public/Tests/Unit/SomeClass/createNewFile.php';
		$this->assertFalse( $this->filesystem->exists( $filename ) );
		$fp = @fopen( $this->filesystem->getUrl( $filename ), 'wb' );
		if ( $fp ) {
			fclose( $fp );
		}
		// Yes, it created the new virtual file.
		$this->assertTrue( $this->filesystem->exists( $filename ) );
		// Yes, it adds the content to the new file.
		$contents = 'Praesent a nibh in nulla dapibus gravida.';
		$this->assertTrue( $this->filesystem->put_contents( $filename, $contents ) );
		$this->assertSame( $contents, $this->filesystem->get_contents( $filename ) );
	}
}
