<?php

namespace WPMedia\PHPUnit\Integration;

use WP_Rest_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_UnitTestCase;

abstract class RESTfulTestCase extends TestCase {
	/**
	 * Instance of the WordPress REST Server.
	 * @var WP_REST_Server
	 */
	protected $server;

	/**
	 * Setup the WP REST API Server.
	 */
	public function setUp() {
		parent::setUp();
		/**
		 * @var WP_REST_Server $wp_rest_server
		 */
		global $wp_rest_server;
		$this->server = $wp_rest_server = new WP_REST_Server;
		do_action( 'rest_api_init' );
	}

	/**
	 * Does the REST request.
	 *
	 * @since 1.0.0
	 *
	 * @param array $body_params Body parameters.
	 * @param  string     $route Requested route.
	 *
	 * @return WP_REST_Response REST response.
	 */
	protected function doRestRequest( array $body_params, $route ) {
		$request = new WP_Rest_Request( 'PUT', $route );
		$request->set_header( 'Content-Type', 'application/x-www-form-urlencoded' );
		$request->set_body_params( $body_params );

		return rest_do_request( $request )->get_data();
	}
}
