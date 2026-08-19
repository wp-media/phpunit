<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\TestCaseTrait;

/**
 * @covers \WPMedia\PHPUnit\TestCaseTrait::configTestData
 * @group  TestCaseTrait
 */
class Test_ConfigTestData extends TestCase {

	public function testShouldReturnTestDataKeyWhenPresentInFixture() {
		$this->assertSame(
			[
				[
					'input'    => 'foo',
					'expected' => 'bar',
				],
			],
			$this->configTestData()
		);
	}

	public function testShouldReturnWholeConfigWhenTestDataKeyIsAbsent() {
		$this->config = [
			'foo' => 'bar',
		];

		$this->assertSame( [ 'foo' => 'bar' ], $this->configTestData() );
	}

	public function testShouldNotReloadConfigWhenAlreadyPopulated() {
		// First call: lazily loads the fixture.
		$this->configTestData();

		// Overwrite with a sentinel value that has no `test_data` key.
		$this->config = [
			'sentinel' => true,
		];

		// Second call must not reload from the fixture, since $config is no longer empty.
		$this->assertSame( [ 'sentinel' => true ], $this->configTestData() );
	}
}
