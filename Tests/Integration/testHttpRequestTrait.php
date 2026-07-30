<?php

namespace WPMedia\PHPUnit\Tests\Integration;

use PHPUnit\Framework\AssertionFailedError;
use WP_Error;
use WPMedia\PHPUnit\Integration\HttpRequestTrait;
use WPMedia\PHPUnit\Integration\TestCase;

/**
 * @covers \WPMedia\PHPUnit\Integration\HttpRequestTrait
 * @group  HttpRequestTrait
 */
class Test_HttpRequestTrait extends TestCase {

	use HttpRequestTrait;

	/**
	 * Fixture consumed by HttpRequestTrait. Set in each test.
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * URL hosts below are unresolvable on purpose: should the trait ever fail open, the request
	 * errors out instead of reaching a real server.
	 */
	const MOCKED_URL = 'https://mocked.invalid/api';
	const OTHER_URL  = 'https://other.invalid/api';
	const UNMOCKED_URL = 'https://unmocked.invalid/api';

	public function set_up() {
		parent::set_up();

		$this->setup_http();
	}

	public function tear_down() {
		$this->tear_down_http();

		parent::tear_down();
	}

	public function testShouldRegisterCallbackOnSetup() {
		$this->assertSame( 10, has_filter( 'pre_http_request', [ $this, 'http_callback' ] ) );
	}

	public function testShouldRemoveCallbackOnTearDown() {
		$this->tear_down_http();

		$this->assertFalse( has_filter( 'pre_http_request', [ $this, 'http_callback' ] ) );
	}

	public function testShouldReturnMockedResponseWhenUrlIsMocked() {
		$this->config = [ 'http' => [ self::MOCKED_URL => $this->response( '{"ok":true}' ) ] ];

		$response = wp_remote_get( self::MOCKED_URL );

		$this->assertSame( $this->response( '{"ok":true}' ), $response );
		$this->assertSame( '{"ok":true}', wp_remote_retrieve_body( $response ) );
		$this->assertSame( 200, wp_remote_retrieve_response_code( $response ) );
	}

	public function testShouldMockEachUrlIndependently() {
		$this->config = [
			'http' => [
				self::MOCKED_URL => $this->response( 'first' ),
				self::OTHER_URL  => $this->response( 'second', 404 ),
			],
		];

		$this->assertSame( 'first', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertSame( 'second', wp_remote_retrieve_body( wp_remote_get( self::OTHER_URL ) ) );
		$this->assertSame( 404, wp_remote_retrieve_response_code( wp_remote_get( self::OTHER_URL ) ) );
	}

	public function testShouldReuseSingleResponseForEveryRequestToSameUrl() {
		$this->config = [ 'http' => [ self::MOCKED_URL => $this->response( 'same' ) ] ];

		foreach ( range( 1, 3 ) as $unused ) {
			$this->assertSame( 'same', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		}
	}

	public function testShouldReturnListedResponsesInOrder() {
		$this->config = [
			'http' => [
				self::MOCKED_URL => [
					$this->response( 'pending' ),
					$this->response( 'done' ),
				],
			],
		];

		$this->assertSame( 'pending', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertSame( 'done', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
	}

	public function testShouldWalkEachUrlListSeparately() {
		$this->config = [
			'http' => [
				self::MOCKED_URL => [ $this->response( 'a1' ), $this->response( 'a2' ) ],
				self::OTHER_URL  => [ $this->response( 'b1' ), $this->response( 'b2' ) ],
			],
		];

		$this->assertSame( 'a1', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertSame( 'b1', wp_remote_retrieve_body( wp_remote_get( self::OTHER_URL ) ) );
		$this->assertSame( 'a2', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertSame( 'b2', wp_remote_retrieve_body( wp_remote_get( self::OTHER_URL ) ) );
	}

	public function testShouldReturnMockedWpErrorAsIs() {
		$error = new WP_Error( 'http_request_failed', 'Timed out' );

		$this->config = [ 'http' => [ self::MOCKED_URL => $error ] ];

		$this->assertSame( $error, wp_remote_get( self::MOCKED_URL ) );
	}

	public function testShouldReturnMockedWpErrorFromWithinAList() {
		$error = new WP_Error( 'http_request_failed', 'Timed out' );

		$this->config = [ 'http' => [ self::MOCKED_URL => [ $this->response( 'ok' ), $error ] ] ];

		$this->assertSame( 'ok', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertSame( $error, wp_remote_get( self::MOCKED_URL ) );
	}

	public function testShouldBlockRequestWhenUrlIsNotMocked() {
		$this->config = [ 'http' => [ self::MOCKED_URL => $this->response( 'ok' ) ] ];

		$this->assertBlocked( wp_remote_get( self::UNMOCKED_URL ) );
		$this->assertStringContainsString(
			self::UNMOCKED_URL . ' (no fixture entry)',
			$this->captureTearDownFailure()
		);
	}

	public function testShouldBlockRequestWhenResponseListIsExhausted() {
		$this->config = [ 'http' => [ self::MOCKED_URL => [ $this->response( 'only' ) ] ] ];

		$this->assertSame( 'only', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
		$this->assertBlocked( wp_remote_get( self::MOCKED_URL ) );
		$this->assertStringContainsString(
			self::MOCKED_URL . ' (request #2, but the fixture only lists 1 response(s))',
			$this->captureTearDownFailure()
		);
	}

	/**
	 * A fixture with no `http` key must block rather than emit a PHP warning. The integration suite
	 * converts warnings to exceptions, so a regression here fails this test rather than passing quietly.
	 *
	 * @dataProvider providerConfigWithoutMocks
	 */
	public function testShouldBlockRequestWhenFixtureDeclaresNoMocks( $config ) {
		$this->config = $config;

		$this->assertBlocked( wp_remote_get( self::UNMOCKED_URL ) );
		$this->assertStringContainsString( self::UNMOCKED_URL, $this->captureTearDownFailure() );
	}

	public function providerConfigWithoutMocks() {
		return [
			'no config at all'   => [ null ],
			'no http key'        => [ [ 'html' => '<p>Hello</p>' ] ],
			'empty http key'     => [ [ 'http' => [] ] ],
			'http is not array'  => [ [ 'http' => 'nope' ] ],
		];
	}

	public function testShouldNotFailTearDownWhenEveryRequestIsMocked() {
		$this->config = [ 'http' => [ self::MOCKED_URL => $this->response( 'ok' ) ] ];

		wp_remote_get( self::MOCKED_URL );

		$this->assertSame( '', $this->captureTearDownFailure() );
	}

	public function testShouldReportEachBlockedUrlOnceAndListThemAll() {
		$this->config = [];

		wp_remote_get( self::UNMOCKED_URL );
		wp_remote_get( self::UNMOCKED_URL );
		wp_remote_get( self::OTHER_URL );

		$message = $this->captureTearDownFailure();

		$this->assertSame( 1, substr_count( $message, self::UNMOCKED_URL ) );
		$this->assertSame( 1, substr_count( $message, self::OTHER_URL ) );
	}

	public function testShouldForgetBlockedRequestsOnceReported() {
		$this->config = [];

		wp_remote_get( self::UNMOCKED_URL );

		$this->assertNotSame( '', $this->captureTearDownFailure() );
		$this->assertSame( '', $this->captureTearDownFailure() );
	}

	public function testShouldRestartResponseListOnSetup() {
		$this->config = [ 'http' => [ self::MOCKED_URL => [ $this->response( 'first' ), $this->response( 'second' ) ] ] ];

		$this->assertSame( 'first', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );

		// Simulate the next test in the class starting over.
		$this->tear_down_http();
		$this->setup_http();

		$this->assertSame( 'first', wp_remote_retrieve_body( wp_remote_get( self::MOCKED_URL ) ) );
	}

	/**
	 * Asserts the request was blocked by the trait rather than attempted.
	 *
	 * @param array|WP_Error $response Value returned by wp_remote_get().
	 */
	private function assertBlocked( $response ) {
		$this->assertWPError( $response );
		$this->assertSame( 'wpmedia_phpunit_unmocked_http_request', $response->get_error_code() );
	}

	/**
	 * Runs the teardown assertion and returns the failure message it produced, or an empty string
	 * when it passed. Also clears the trait's recorded state, so the real tear_down() stays quiet.
	 *
	 * @return string
	 */
	private function captureTearDownFailure() {
		try {
			$this->tear_down_http();
		} catch ( AssertionFailedError $e ) {
			return $e->getMessage();
		}

		return '';
	}

	/**
	 * Builds a raw WordPress HTTP response array.
	 *
	 * @param string $body Response body.
	 * @param int    $code Response status code.
	 *
	 * @return array
	 */
	private function response( $body, $code = 200 ) {
		return [
			'headers'  => [],
			'body'     => $body,
			'response' => [
				'code'    => $code,
				'message' => get_status_header_desc( $code ),
			],
			'cookies'  => [],
		];
	}
}
