<?php

namespace WPMedia\PHPUnit\Tests\Integration;

use WP_Error;
use WPMedia\PHPUnit\Integration\HttpRequestTrait;
use WPMedia\PHPUnit\Integration\TestCase;

/**
 * Base that declares `protected array $blocked_http_requests`, mirroring the property WordPress 7.2
 * added to WP_UnitTestCase_Base (`@since 7.2.0`). Declaring it here reproduces the composition clash
 * of issue #62 on any installed WordPress version, not only on WP 7.2+. It extends the integration
 * TestCase so the composing test class below has the PHPUnit and WordPress API the trait relies on
 * (`fail()`, `add_filter()`, `WP_Error`).
 */
abstract class HttpRequestTraitPropertyCompositionTestCase extends TestCase {

	/**
	 * Mirrors WP_UnitTestCase_Base::$blocked_http_requests (WP 7.2+).
	 *
	 * @var string[]
	 */
	protected array $blocked_http_requests = [];
}

/**
 * Regression guard for issue #62: HttpRequestTrait must compose onto a base test case that declares
 * its own `$blocked_http_requests` (WordPress 7.2+) without a fatal property-composition error, and
 * its own unmocked-request bookkeeping must keep working independently of that inherited property.
 *
 * Before the fix the trait's `private $blocked_http_requests` clashes with the inherited property and
 * PHP cannot compose this class, so merely loading this file fatals. After the fix the trait owns
 * `$unmocked_http_requests` and composition succeeds.
 *
 * @covers \WPMedia\PHPUnit\Integration\HttpRequestTrait
 * @group  HttpRequestTrait
 */
class Test_HttpRequestTraitPropertyComposition extends HttpRequestTraitPropertyCompositionTestCase {

	use HttpRequestTrait;

	/**
	 * Fixture consumed by HttpRequestTrait.
	 *
	 * @var array
	 */
	protected $config;

	public function testShouldComposeTraitOntoBaseDeclaringBlockedHttpRequests() {
		// Reaching this assertion at all proves the class composed without the property-composition
		// fatal, despite the inherited protected `$blocked_http_requests`.
		$this->assertContains( HttpRequestTrait::class, class_uses( $this ) );
	}

	public function testShouldBlockUnmockedRequestIndependentlyOfInheritedProperty() {
		$this->config = [];

		$result = $this->http_callback( false, [], 'https://unmocked.invalid/api' );

		$this->assertInstanceOf( WP_Error::class, $result );
		$this->assertSame( 'wpmedia_phpunit_unmocked_http_request', $result->get_error_code() );
	}
}
