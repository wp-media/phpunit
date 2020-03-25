<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getListing
 * @group  VirtualFilesystemDirect
 * @group  listing
 */
class Test_GetListing extends TestCase {

	/**
	 * @dataProvider addDataProvider
	 */
	public function testShouldReturnListingOfAllFilesAndDirs( $dir, $expected ) {
		$this->assertSame( $expected, $this->filesystem->getListing( $dir ) );
	}

	public function addDataProvider() {
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
				'Tests/Unit',
				[
					'vfs://public/Tests/Unit/bootstrap.php',
					'vfs://public/Tests/Unit/SomeClass/',
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
				],
			],
			[
				'Tests/',
				[
					'vfs://public/Tests/Integration/',
					'vfs://public/Tests/Unit/',
					'vfs://public/Tests/Unit/bootstrap.php',
					'vfs://public/Tests/Unit/SomeClass/',
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
					'vfs://public/Tests/includes/',
					'vfs://public/Tests/TestCase.php',
				],
			],
			[
				'/',
				[
					'vfs://public/Tests/',
					'vfs://public/Tests/Integration/',
					'vfs://public/Tests/Unit/',
					'vfs://public/Tests/Unit/bootstrap.php',
					'vfs://public/Tests/Unit/SomeClass/',
					'vfs://public/Tests/Unit/SomeClass/getFile.php',
					'vfs://public/Tests/includes/',
					'vfs://public/Tests/TestCase.php',
					'vfs://public/baz/',
					'vfs://public/baz/index.html',
				],
			],
		];
	}
}
