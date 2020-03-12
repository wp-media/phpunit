<?php

namespace WPMedia\PHPUnit\Integration;

abstract class AdminTestCase extends TestCase {
	/**
	 * Override in your test case or class to specify the current screen.
	 * @var string
	 */
	protected $screen = 'options-general.php?page=wprocket';
	protected $original_error_level = 0;

	public static function setUpBeforeClass() {
		remove_action( 'admin_init', '_maybe_update_core' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_themes' );

		static::initBeforeClass();
		parent::setUpBeforeClass();
	}

	public static function initBeforeClass() {
		// Placeholder if needed.
	}

	public function setUp() {
		parent::setUp();

		set_current_screen( $this->screen );
		add_action( 'clear_auth_cookie', [ $this, 'clear_cookies_and_user' ] );

		// Suppress warnings from "Cannot modify header information - headers already sent by".
		$this->original_error_level = error_reporting();
		error_reporting( $this->_error_level & ~E_WARNING );

		do_action( 'admin_init' );
	}

	public function tearDown() {
		parent::tearDown();

		$_POST = [];
		$_GET  = [];
		unset( $GLOBALS['post'], $GLOBALS['comment'] );

		remove_action( 'clear_auth_cookie', [ $this, 'clear_cookies_and_user' ] );
		error_reporting( $this->original_error_level );
		set_current_screen( 'front' );
	}

	public function clear_cookies_and_user() {
		unset( $GLOBALS['current_user'] );

		foreach ( [ AUTH_COOKIE, SECURE_AUTH_COOKIE, LOGGED_IN_COOKIE, USER_COOKIE, PASS_COOKIE ] as $cookie ) {
			unset( $_COOKIE[ $cookie ] );
		}
	}
}
