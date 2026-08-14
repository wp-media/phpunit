<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemTestTrait;

/**
 * Tests the default virtual filesystem structure.
 *
 * @covers \WPMedia\PHPUnit\VirtualFilesystemTestTrait::getDefaultVfs
 * @group  VfsTrait
 */
class Test_GetDefaultVfs extends TestCase {

	/**
	 * Asserts that getDefaultVfs() returns the expected default structure.
	 *
	 * @dataProvider getDefaultVfsDataProvider
	 *
	 * @param array $expected Expected default structure.
	 *
	 * @return void
	 */
	public function testShouldReturnTheDefaultStructure( $expected ) {
		$this->assertSame( $expected, $this->getDefaultVfs() );
	}

	/**
	 * Provides the expected default structure from the Fixtures directory.
	 *
	 * @return array test data.
	 */
	public function getDefaultVfsDataProvider() {
		return $this->getTestData( __DIR__, 'getDefaultVfs' );
	}
}
