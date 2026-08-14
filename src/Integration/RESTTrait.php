<?php

namespace WPMedia\PHPUnit\Integration;

use WP_Rest_Request;
use WP_REST_Response;
use WP_REST_Server;

trait RESTTrait {

	/**
	 * Instance of the WordPress REST Server.
	 *
	 * @var WP_REST_Server
	 */
	protected $server;

	/**
	 * Sets up the WP REST API Server.
	 */
	protected function setUpServer() {
		/**
		 * @var WP_REST_Server $wp_rest_server
		 */
		$this->server = rest_get_server();
	}

	/**
	 * Does the REST DELETE request.
	 *
	 * @param string $route       Requested route.
	 * @param array  $body_params Optional. Body parameters.
	 *
	 * @return mixed Response data.
	 */
	protected function doRestDelete( $route, array $body_params = [] ) {
		return $this->doRestRequest( 'DELETE', $route, $body_params );
	}

	/**
	 * Does the REST PUT request.
	 *
	 * @param string $route       Requested route.
	 * @param array  $body_params Optional. Body parameters.
	 *
	 * @return mixed Response data.
	 */
	protected function doRestPut( $route, array $body_params = [] ) {
		return $this->doRestRequest( 'PUT', $route, $body_params );
	}

	/**
	 * Does the REST request.
	 *
	 * @since 1.1.6 Adds REST method and changes the order.
	 * @since 1.0.0
	 *
	 * @param string $method      REST method.
	 * @param string $route       Requested route.
	 * @param array  $body_params Optional. Body parameters.
	 *
	 * @return mixed Response data.
	 */
	protected function doRestRequest( $method, $route, array $body_params = [] ) {
		$request = new WP_Rest_Request( $method, $route );
		$request->set_header( 'Content-Type', 'application/x-www-form-urlencoded' );

		if ( ! empty( $body_params ) ) {
			$request->set_body_params( $body_params );
		}

		return rest_do_request( $request )->get_data();
	}
}
