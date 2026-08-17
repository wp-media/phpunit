<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

use ReflectionClass;
use WPMedia\PHPUnit\BootstrapManager as Subject;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::getRootDir
 * @group  BootstrapManager
 */
class Test_GetRootDir extends TestCase {

	/**
	 * @dataProvider getRootDirDataProvider
	 */
	public function testShouldResolveTheRootDir( $root, $expected ) {
		// Resolve the install-location tokens from the fixture against the package's real src/ dir.
		$src_dir  = dirname( ( new ReflectionClass( Subject::class ) )->getFileName() );
		$resolved = [
			'{{consumer_root}}' => dirname( dirname( dirname( dirname( $src_dir ) ) ) ),
			'{{package_root}}'  => dirname( $src_dir ),
		];

		if ( is_string( $expected ) && isset( $resolved[ $expected ] ) ) {
			$expected = $resolved[ $expected ];
		}

		$this->assertSame( $expected, $this->invoke( 'getRootDir', [ $root ] ) );
	}

	public function getRootDirDataProvider() {
		return $this->getTestData( __DIR__, 'getRootDir' );
	}
}
