<?php

namespace WPMedia\PHPUnit\Tests\Integration\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPIntegration\Tests\Unit\VirtualFilesystemDirect::gethchmod
 * @group  VirtualFilesystemDirect
 */
class Test_Gethchmod extends TestCase {
	protected $path_to_test_data = 'gethchmod.php';

	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldReturnModeWhenFileExists( $file, $expected ) {
		$this->assertSame( $expected, $this->filesystem->gethchmod( $file ) );
	}
}
