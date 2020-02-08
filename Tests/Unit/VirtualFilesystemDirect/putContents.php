<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::put_contents
 * @group  VirtualFilesystemDirect
 */
class Test_PutContents extends TestCase {

	function testShouldPutContentsWhenFileExists() {
		$original = $this->filesystem->get_contents( 'cache/baz/index.html' );
		$content  = 'New contents';
		$this->assertTrue( $this->filesystem->put_contents( 'cache/baz/index.html', $content ) );
		$this->assertNotSame( $original, $this->filesystem->get_contents( 'cache/baz/index.html' ) );
		$this->assertSame( $content, $this->filesystem->get_contents( 'cache/baz/index.html' ) );

		$original = $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' );
		$content  = 'New contents';
		$this->assertTrue( $this->filesystem->put_contents( 'Tests/Unit/SomeClass/getFile.php', $content ) );
		$this->assertNotSame( $original, $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' ) );
		$this->assertSame( $content, $this->filesystem->get_contents( 'Tests/Unit/SomeClass/getFile.php' ) );
	}

	function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->assertFalse( $this->filesystem->put_contents( 'baz/invalid.html', 'New contents' ) );
		$this->assertFalse( $this->filesystem->put_contents( 'cache/Tests/Unit/invalid.php', 'New contents' ) );
	}
}
