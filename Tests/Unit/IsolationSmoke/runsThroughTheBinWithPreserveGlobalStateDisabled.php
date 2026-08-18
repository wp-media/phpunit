<?php

namespace WPMedia\PHPUnit\Tests\Unit\IsolationSmoke;

use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;

/**
 * Companion to Test_RunsThroughTheBin, added on top of the issue #51 grooming spec.
 *
 * The issue's own reproduction used `@preserveGlobalState disabled`, even though grooming could
 * only reproduce the bug with it left at its default (enabled) — see the spec's "Empirical
 * findings" #1. On the PHPUnit version installed during grooming (9.6), `disabled` never reaches
 * the included-files re-require mechanism at all, so this case cannot currently fail because of
 * issue #51. It is still wired into `composer test-unit-bin-smoke` (via the shared
 * `@group IsolationSmoke`) so that both annotation combinations are exercised through the real
 * bin, across the full CI PHP matrix, in case a future PHPUnit version changes that behaviour.
 *
 * The assertion defines and reads back a constant inside the isolated child, rather than a bare
 * `assertTrue( true )`, so the test genuinely exercises the isolation boundary instead of only
 * confirming the child process returned at all.
 *
 * @group IsolationSmoke
 */
class Test_RunsThroughTheBinWithPreserveGlobalStateDisabled extends BaseTestCase {

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testShouldSurviveIsolationWithGlobalStatePreservationDisabled() {
		// The value is derived at runtime (rather than a literal) so the round trip through
		// define()/constant() genuinely exercises the isolation boundary instead of comparing
		// two values a static analyzer could fold to "always true".
		$value = 'ok-' . getmypid();

		define( 'WPMEDIA_PHPUNIT_ISOLATION_SMOKE_CONSTANT', $value );

		$this->assertTrue( defined( 'WPMEDIA_PHPUNIT_ISOLATION_SMOKE_CONSTANT' ) );
		$this->assertSame( $value, constant( 'WPMEDIA_PHPUNIT_ISOLATION_SMOKE_CONSTANT' ) );
	}
}
