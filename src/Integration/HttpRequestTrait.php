<?php
declare(strict_types=1);

namespace WPMedia\PHPUnit\Integration;

use WP_Error;

/**
 * Mocks outbound HTTP requests during integration tests.
 *
 * Responses are declared in the test's fixture under the `http` key, mapping the requested URL to
 * the value `wp_remote_request()` should return. That value is the raw WordPress response array or a WP_Error. Several URLs can be mocked at once, and a URL can be requested any number of times:
 *
 *     'http' => [
 *         'https://example.org/api'   => [
 *             'headers'  => [],
 *             'body'     => '{"ok":true}',
 *             'response' => [ 'code' => 200, 'message' => 'OK' ],
 *             'cookies'  => [],
 *         ],
 *         'https://example.org/other' => new WP_Error( 'http_request_failed', 'Timed out' ),
 *     ],
 *
 * Keys the code under test does not read can be left out; `body` and `response` are usually enough.
 *
 * When the same URL must answer differently on each call, declare a list of responses. They are
 * returned in order, one per request:
 *
 *     'http' => [
 *         'https://example.org/api' => [
 *             [ 'body' => '{"status":"pending"}', 'response' => [ 'code' => 200 ] ],
 *             [ 'body' => '{"status":"done"}',    'response' => [ 'code' => 200 ] ],
 *         ],
 *     ],
 *
 * Any request to a URL without a fixture entry - or beyond the end of its list of responses - is
 * blocked and fails the test, so a test can never silently hit the network.
 *
 * The consuming test class supplies the fixture through a `$config` property: declare it on the
 * class, or inherit it from a base test case that already provides one, such as
 * VirtualFilesystemTestCase. This trait deliberately does not declare `$config` itself, as PHP
 * rejects composing a trait property with an inherited one whose default value differs.
 */
trait HttpRequestTrait {

	/**
	 * Reasons why requests were blocked during the test, if any.
	 *
	 * @var string[]
	 */
	private $blocked_http_requests = [];

	/**
	 * Number of requests served so far, keyed by URL. Used to walk a list of responses.
	 *
	 * @var int[]
	 */
	private $http_request_counts = [];

	/**
	 * Starts mocking outbound HTTP requests for the current test.
	 *
	 * @return void
	 */
	public function setup_http() {
		$this->reset_http();

		add_filter( 'pre_http_request', [ $this, 'http_callback' ], 10, 3 );
	}

	/**
	 * Stops mocking outbound HTTP requests and fails the test if any request went unmocked.
	 *
	 * @return void
	 */
	public function tear_down_http() {
		remove_filter( 'pre_http_request', [ $this, 'http_callback' ], 10 );

		$blocked = $this->blocked_http_requests;

		$this->reset_http();

		if ( [] === $blocked ) {
			return;
		}

		$this->fail(
			sprintf(
				"The test performed HTTP request(s) the fixture does not mock:\n  - %s\nAdd them to the fixture's `http` config.",
				implode( "\n  - ", array_unique( $blocked ) )
			)
		);
	}

	/**
	 * Short-circuits `wp_remote_request()` with the fixture response for the given URL.
	 *
	 * @param false|array|WP_Error $response Preemptive response. Returning anything but false stops WordPress from
	 *                                       performing the request.
	 * @param array                $args     Request arguments.
	 * @param string               $url      Requested URL.
	 *
	 * @return array|WP_Error The mocked response, or a WP_Error when the request is not mocked.
	 */
	public function http_callback( $response, $args, $url ) {
		$mocked = isset( $this->config['http'] ) && is_array( $this->config['http'] )
			? $this->config['http']
			: [];

		if ( ! array_key_exists( $url, $mocked ) ) {
			return $this->block_http_request( $url, sprintf( '%s (no fixture entry)', $url ) );
		}

		$mock = $mocked[ $url ];

		// A single response is reused for every request to that URL.
		if ( ! $this->is_response_list( $mock ) ) {
			return $mock;
		}

		$index = $this->http_request_counts[ $url ] ?? 0;

		if ( ! array_key_exists( $index, $mock ) ) {
			return $this->block_http_request(
				$url,
				sprintf( '%s (request #%d, but the fixture only lists %d response(s))', $url, $index + 1, count( $mock ) )
			);
		}

		$this->http_request_counts[ $url ] = $index + 1;

		return $mock[ $index ];
	}

	/**
	 * Records a request as unmocked and blocks it.
	 *
	 * @param string $url    Requested URL.
	 * @param string $reason Reason reported when the test tears down.
	 *
	 * @return WP_Error
	 */
	private function block_http_request( $url, $reason ) {
		$this->blocked_http_requests[] = $reason;

		return new WP_Error(
			'wpmedia_phpunit_unmocked_http_request',
			sprintf( 'Blocked unmocked HTTP request to %s.', $url )
		);
	}

	/**
	 * Tells a list of responses apart from a single response.
	 *
	 * A WordPress HTTP response is an associative array (`body`, `response`, `headers`, ...) or a WP_Error, so a
	 * sequentially indexed array can only be a list of responses.
	 *
	 * @param mixed $mock Fixture value for a URL.
	 *
	 * @return bool
	 */
	private function is_response_list( $mock ) {
		if ( ! is_array( $mock ) || [] === $mock ) {
			return false;
		}

		return array_keys( $mock ) === range( 0, count( $mock ) - 1 );
	}

	/**
	 * Clears the state tracked between tests.
	 *
	 * @return void
	 */
	private function reset_http() {
		$this->blocked_http_requests = [];
		$this->http_request_counts   = [];
	}
}
