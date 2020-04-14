<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemTestTrait;

/**
 * @covers \WPMedia\PHPUnit\VirtualFilesystemTestTrait::initOriginals
 * @group  VfsTrait
 */
class Test_InitOriginals extends TestCase {
	protected $path_to_test_data = 'initOriginals.php';

	public function setUp() {
		parent::setUp();

		$this->skip_initOriginals = false;
		$this->config             = [];
		$this->original_dirs      = [];
		$this->original_files     = [];
	}

	public function testShouldNotInitOriginalsWhenSkipIsTrue() {
		$this->skip_initOriginals = true;

		// Run it.
		$this->initOriginals();

		$this->assertEmpty( $this->original_dirs );
		$this->assertEmpty( $this->original_files );
	}

	/**
	 * @dataProvider initOriginalsDataProvider
	 */
	public function testShouldInitOriginals( $config, $expected_dirs, $expected_files ) {
		$this->config = $config;

		// Run it.
		$this->initOriginals();

		$this->assertSame( $expected_dirs, $this->original_dirs );
		$this->assertSame( $expected_files, $this->original_files );
	}

	public function initOriginalsDataProvider() {
		return require $this->getPathToFixturesDir() . 'VirtualFilesystemTestTrait/initOriginals.php';
	}
}
