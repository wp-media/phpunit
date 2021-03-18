<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemTestTrait;

/**
 * @covers \WPMedia\PHPUnit\VirtualFilesystemTestTrait::initOriginals
 * @group  VfsTrait
 */
class Test_LoadConfig extends TestCase {

	public function setUp() : void {
		parent::setUp();

		$this->config = [];
	}

	/**
	 * @dataProvider loadConfigDataProvider
	 */
	public function testShouldLoadConfig( $path_to_test_data, $expected ) {
		$this->path_to_test_data = $path_to_test_data;

		// Run it.
		$this->loadConfig();

		$this->assertSame( $expected, $this->config );
	}

	public function loadConfigDataProvider() {
		return require $this->getPathToFixturesDir() . 'VirtualFilesystemTestTrait/loadConfig.php';
	}
}
