<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getFilesListing
 * @group  VirtualFilesystemDirect
 * @group  listing
 */
class Test_GetFilesListing extends TestCase {

	/**
	 * @dataProvider listingTestData
	 */
	public function testShouldReturnListingOfAllFiles( $dir, $expected ) {
		$this->assertSame( $expected, $this->filesystem->getFilesListing( $dir ) );
	}

	public function listingTestData() {
		return [
			[
				'Tests/Integration/',
				[],
			],
			[
				'baz/',
				[
					'vfs://public/baz/index.html',
				],
			],
			[
				'Tests/Unit/SomeClass',
				[
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
				],
			],
			[
				'Tests/Unit/',
				[
					'vfs://public/Tests/Unit/bootstrap.php',
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
				],
			],
			[
				'Tests/',
				[
					'vfs://public/Tests/Unit/bootstrap.php',
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
					'vfs://public/Tests/TestCase.php',
				],
			],
			[
				'/',
				[
					'vfs://public/Tests/Unit/bootstrap.php',
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
					'vfs://public/Tests/TestCase.php',
					'vfs://public/baz/index.html',
				],
			],
		];
	}
}
