<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect::getDirsListing
 * @group  VirtualFilesystemDirect
 * @group  listing
 */
class Test_GetDirsListing extends TestCase {

	/**
	 * @dataProvider addDataProvider
	 */
	public function testShouldReturnListingOfAllDirs( $dir, $expected ) {
		$this->assertSame( $expected, $this->filesystem->getDirsListing( $dir ) );
	}

	public function addDataProvider() {
		return [
			[
				'Tests/Integration/',
				[],
			],
			[
				'baz/',
				[],
			],
			[
				'Tests/Unit',
				[
					'vfs://public/Tests/Unit/SomeClass/',
				],
			],
			[
				'Tests/',
				[
					'vfs://public/Tests/Integration/',
					'vfs://public/Tests/Unit/',
					'vfs://public/Tests/Unit/SomeClass/',
					'vfs://public/Tests/includes/',
				],
			],
			[
				'/',
				[
					'vfs://public/Tests/',
					'vfs://public/Tests/Integration/',
					'vfs://public/Tests/Unit/',
					'vfs://public/Tests/Unit/SomeClass/',
					'vfs://public/Tests/includes/',
					'vfs://public/baz/',
				],
			],
		];
	}
}
