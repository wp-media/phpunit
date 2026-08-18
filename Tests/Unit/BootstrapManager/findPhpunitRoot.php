<?php

namespace WPMedia\PHPUnit\Tests\Unit\BootstrapManager;

use ReflectionClass;
use WPMedia\PHPUnit\BootstrapManager as Subject;

/**
 * @covers WPMedia\PHPUnit\BootstrapManager::findPhpunitRoot
 * @group  BootstrapManager
 */
class Test_FindPhpunitRoot extends TestCase {

	/**
	 * findPhpunitRoot() has no parameters — it always resolves against the two real,
	 * hardcoded-by-__DIR__ candidate locations (the consumer install layout, then this
	 * package's own vendor/ for the self-test). There is no filesystem-safe way to fake either
	 * candidate without mutating the real install this suite runs from, so this asserts the
	 * method against the same two candidates it actually checks, computed independently here.
	 */
	public function testShouldResolveTheRootContainingBinPhpunit() {
		$src_dir = dirname( ( new ReflectionClass( Subject::class ) )->getFileName() );

		$candidates = [
			dirname( dirname( dirname( $src_dir ) ) ),
			dirname( $src_dir ) . '/vendor',
		];

		$expected = false;
		foreach ( $candidates as $candidate ) {
			if ( is_readable( "{$candidate}/bin/phpunit" ) ) {
				$expected = $candidate;
				break;
			}
		}

		$this->assertNotFalse(
			$expected,
			'Expected at least one candidate root to contain a readable bin/phpunit in this test environment.'
		);
		$this->assertSame( $expected, $this->invoke( 'findPhpunitRoot' ) );
	}
}
