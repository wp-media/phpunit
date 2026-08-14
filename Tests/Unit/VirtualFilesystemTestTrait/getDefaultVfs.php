<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemTestTrait;

/**
 * @covers \WPMedia\PHPUnit\VirtualFilesystemTestTrait::getDefaultVfs
 * @group  VfsTrait
 */
class Test_GetDefaultVfs extends TestCase {

	public function testShouldReturnTheDefaultStructure() {
		$expected = [
			'Tests' => [
				'Integration' => [],
				'Unit'        => [],
			],
		];

		$this->assertSame( $expected, $this->getDefaultVfs() );
	}

	public function testShouldBeUsedAsTheBaseForTheMergedStructure() {
		$this->config = [
			'structure' => [
				'baz' => '',
			],
		];

		$merged = $this->mergeStructure();

		$this->assertArrayHasKey( 'Tests', $merged );
		$this->assertArrayHasKey( 'baz', $merged );
	}
}
