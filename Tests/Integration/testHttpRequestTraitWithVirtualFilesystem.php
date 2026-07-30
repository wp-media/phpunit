<?php

namespace WPMedia\PHPUnit\Tests\Integration;

use WPMedia\PHPUnit\Integration\HttpRequestTrait;
use WPMedia\PHPUnit\Integration\VirtualFilesystemTestCase;
use WPMedia\PHPUnit\VirtualFilesystemDirect;

/**
 * Guards the composition of HttpRequestTrait with a test case that already provides `$config`.
 *
 * PHP refuses to compose a trait property with an inherited one when their default values differ,
 * which is a fatal error at class-composition time - this file would not even load. Test cases
 * combining a virtual filesystem with mocked HTTP are a common case, so it is covered here.
 *
 * @covers \WPMedia\PHPUnit\Integration\HttpRequestTrait
 * @group  HttpRequestTrait
 */
class Test_HttpRequestTraitWithVirtualFilesystem extends VirtualFilesystemTestCase {

	use HttpRequestTrait;

	protected $path_to_test_data = 'structure.php';

	const MOCKED_URL = 'https://mocked.invalid/api';

	public function set_up() {
		parent::set_up();

		$this->init();
		$this->setup_http();
	}

	public function tear_down() {
		$this->tear_down_http();

		parent::tear_down();
	}

	public function getPathToFixturesDir() {
		return WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}

	public function testShouldLoadVirtualFilesystemConfigAlongsideTheTrait() {
		$this->assertInstanceOf( VirtualFilesystemDirect::class, $this->filesystem );
		$this->assertArrayHasKey( 'structure', $this->config );
	}

	public function testShouldMockHttpWhileVirtualFilesystemIsInUse() {
		$this->config['http'] = [
			self::MOCKED_URL => [
				'body'     => 'from the fixture',
				'response' => [ 'code' => 200 ],
			],
		];

		$this->assertSame( 'from the fixture', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertTrue( $this->filesystem->is_dir( $this->rootVirtualUrl . 'Tests' ) );
	}
}
