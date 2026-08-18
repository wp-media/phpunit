<?php

namespace WPMedia\PHPUnit\Tests\Unit\IsolationSmoke;

use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;

/**
 * End-to-end regression guard for issue #51. Only meaningful when run via
 * `composer test-unit-bin-smoke` (the real wpmedia-phpunit bin); under the ordinary
 * `composer test-unit` self-test (which bypasses the bin, per CLAUDE.md's "Self-test caveat")
 * it always passes regardless of the fix and proves nothing.
 *
 * Deliberately NOT @preserveGlobalState disabled: grooming confirmed empirically that the
 * bug this issue describes only manifests when preserveGlobalState is left at its default
 * (enabled) — PHPUnit never consults get_included_files()/the isolation exclude list at all
 * when preserveGlobalState is disabled, so a "disabled" variant would pass unconditionally
 * and would not guard against a regression.
 *
 * @group IsolationSmoke
 */
class Test_RunsThroughTheBin extends BaseTestCase {

	/**
	 * @runInSeparateProcess
	 */
	public function testShouldSurviveIsolationWhenRunThroughTheBin() {
		$this->assertTrue( defined( 'WPMEDIA_PHPUNIT_ROOT_DIR' ) );
	}
}
