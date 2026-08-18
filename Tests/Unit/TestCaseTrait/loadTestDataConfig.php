<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\TestCaseTrait;

/**
 * @covers \WPMedia\PHPUnit\TestCaseTrait::loadTestDataConfig
 * @group  TestCaseTrait
 */
class Test_LoadTestDataConfig extends TestCase {

	public function testShouldPopulateConfigFromMatchingFixture() {
		$this->loadTestDataConfig();

		$this->assertSame(
			[
				'structure' => [
					'foo' => 'bar',
				],
				'test_data' => [
					'baz' => 'qux',
				],
			],
			$this->config
		);
	}

	public function testShouldSetEmptyConfigWhenNoMatchingFixtureFileExists() {
		$target = new NoFixtureTestCase();

		$method = $this->get_reflective_method( 'loadTestDataConfig', NoFixtureTestCase::class );
		$method->invoke( $target );

		$this->assertSame( [], $this->getNonPublicPropertyValue( 'config', NoFixtureTestCase::class, $target ) );
	}
}
