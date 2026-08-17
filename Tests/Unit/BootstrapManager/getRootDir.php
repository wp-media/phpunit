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
	 * Absolute path to the package's `src` directory (where BootstrapManager lives).
	 *
	 * @return string
	 */
	private function srcDir() {
		return dirname( ( new ReflectionClass( Subject::class ) )->getFileName() );
	}

	public function testShouldWalkUpToTheConsumerRootWhenNoRootGiven() {
		// Installed as a dependency at vendor/wp-media/phpunit/src, four levels up from src is
		// the consumer project's root.
		$expected = dirname( dirname( dirname( dirname( $this->srcDir() ) ) ) );

		$this->assertSame( $expected, $this->invoke( 'getRootDir', [ false ] ) );
	}

	public function testShouldReturnThePackageRootWhenRootIsDot() {
		$this->assertSame(
			dirname( $this->srcDir() ),
			$this->invoke( 'getRootDir', [ [ 'WPMEDIA_PHPUNIT_ROOT_DIR' => '.' ] ] )
		);
	}

	public function testShouldStripLeadingSlashFromAnExplicitRoot() {
		$this->assertSame(
			'var/www/plugin',
			$this->invoke( 'getRootDir', [ [ 'WPMEDIA_PHPUNIT_ROOT_DIR' => '/var/www/plugin' ] ] )
		);
	}

	public function testShouldReturnARelativeRootUnchanged() {
		$this->assertSame(
			'relative/path/to/plugin',
			$this->invoke( 'getRootDir', [ [ 'WPMEDIA_PHPUNIT_ROOT_DIR' => 'relative/path/to/plugin' ] ] )
		);
	}
}
