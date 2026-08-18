<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

use ReflectionClass;
use WPMedia\PHPUnit\BootstrapManager as Subject;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::registerIsolationExcludeList
 * @group  BootstrapManager
 */
class Test_RegisterIsolationExcludeList extends TestCase {

	/**
	 * Snapshot of the isolation exclude list, taken before each test.
	 *
	 * @var array|null
	 */
	private $original_exclude_list;

	protected function setUp(): void {
		parent::setUp();

		$this->original_exclude_list = isset( $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] )
			? $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST']
			: null;
	}

	protected function tearDown(): void {
		if ( null === $this->original_exclude_list ) {
			unset( $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] );
		} else {
			$GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] = $this->original_exclude_list;
		}

		parent::tearDown();
	}

	public function testShouldRegisterAllCandidateRealpaths() {
		unset( $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] );

		$src_dir      = dirname( ( new ReflectionClass( Subject::class ) )->getFileName() );
		$package_root = dirname( $src_dir );
		$phpunit_root = $this->invoke( 'findPhpunitRoot' );

		$expected = [
			realpath( "{$package_root}/wpmedia-phpunit" ),
			realpath( "{$src_dir}/BootstrapManager.php" ),
		];

		if ( false !== $phpunit_root ) {
			$expected[] = realpath( "{$phpunit_root}/bin/phpunit" );
			$expected[] = realpath( "{$phpunit_root}/phpunit/phpunit/phpunit" );
		}

		// realpath() returns false for a nonexistent candidate; registerIsolationExcludeList()
		// skips those rather than pushing false into the list.
		$expected = array_values( array_filter( $expected, 'is_string' ) );

		Subject::registerIsolationExcludeList();

		$this->assertSame( $expected, $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] );
	}

	public function testShouldAppendToAPreExistingListRatherThanOverwriteIt() {
		$GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] = [ '/already/registered/by/vendor/bin/phpunit' ];

		Subject::registerIsolationExcludeList();

		$this->assertContains(
			'/already/registered/by/vendor/bin/phpunit',
			$GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST']
		);
		$this->assertGreaterThan( 1, count( $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] ) );
	}
}
