<?php

namespace WPMedia\PHPUnit;

class BootstrapManager {

	/**
	 * Builds up the command line arguments that PHPUnit needs and then loads phpunit.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which_testsuite Which test suite to run: "unit" or "integration".
	 */
	public static function runTestSuite( $which_testsuite ) {
		$_SERVER['argv'] = $GLOBALS['argv'] = self::getConfigArgv( $which_testsuite );
		$_SERVER['argc'] = $GLOBALS['argc'] = count( $GLOBALS['argv'] );

		// Find and load PHPUnit.
		foreach ( [ dirname( dirname( __DIR__ ) ), __DIR__ . '/vendor' ] as $root ) {
			if ( is_readable( "{$root}/bin/phpunit" ) ) {
				require_once "{$root}/bin/phpunit";
				return;
			}
		}
	}

	/**
	 * Sets up the constants for the test suite.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which_testsuite Which test suite to run: "unit" or "integration".
	 */
	public static function setupConstants( $which_testsuite ) {
		define( 'WPMEDIA_PHPUNIT_ROOT_DIR', self::getRootDir( self::getArg( 'WPMEDIA_PHPUNIT_ROOT_DIR' ) ) );

		$path = self::getArg( 'path' );
		if ( false !== $path ) {
			$path = rtrim( $path['path'], '/\\' );
		} else {
			$path = 'Tests/' . ucfirst( $which_testsuite );
		}
		define( 'WPMEDIA_PHPUNIT_ROOT_TEST_DIR', WPMEDIA_PHPUNIT_ROOT_DIR . DIRECTORY_SEPARATOR . $path );
	}

	/**
	 * Gets the absolute path to the phpunit.xml.dist file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $test_dir Name of the test directory, i.e. Unit, unit, Integration, integration.
	 *
	 * @return string the absolute path to the phpunit.xml.dist file.
	 */
	protected static function getPhpunitXml( $test_dir ) {
		if ( is_readable( WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/phpunit.xml.dist' ) ) {
			return WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/phpunit.xml.dist';
		}

		return __DIR__ . "/{$test_dir}/phpunit.xml.dist";
	}

	/**
	 * Gets the configuration to build the command line arguments for which test to run.
	 *
	 * @since 1.0.0
	 *
	 * @param string $test unit or integration.
	 *
	 * @return array command line arguments for $argv.
	 */
	protected static function getConfigArgv( $test ) {
		$script = [];

		if ( 'unit' === $test ) {
			$script = [
				'vendor/bin/phpunit',
				'--testsuite',
				'unit',
				'--colors=always',
				'--configuration',
				self::getPhpunitXml( 'Unit' ),
			];
		} elseif ( 'integration' === $test ) {
			$script = [
				'vendor/bin/phpunit',
				'--testsuite',
				'integration',
				'--colors=always',
				'--configuration',
				self::getPhpunitXml( 'Integration' ),
			];
		}

		self::removeCliArg( 'path' );
		self::removeCliArg( 'WPMEDIA_PHPUNIT_ROOT_DIR' );

		if ( 2 === $_SERVER['argc'] ) {
			return $script;
		}

		// Add in additional command line arguments.
		foreach ( array_slice( $_SERVER['argv'], 2 ) as $arg ) {
			$script[] = $arg;
		}

		return $script;
	}

	/**
	 * Removes the given argument from the command line arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The command line argument "key" to be stripped out.
	 */
	protected static function removeCliArg( $key ) {
		$arg = self::getArg( $key );
		if ( false === $arg ) {
			return;
		}

		unset( $_SERVER['argv'][ $arg['index'] ] );
	}

	/**
	 * Description.
	 *
	 * @since 1.0.0
	 *
	 * @param $key
	 *
	 * @return array|bool
	 */
	protected static function getArg( $key ) {
		foreach ( $_SERVER['argv'] as $index => $arg ) {
			if ( $key === substr( $arg, 0, strlen( $key ) ) ) {
				return [ 'index' => $index, $key => str_replace( "{$key}=", '', $arg ) ];
			}
		}

		return false;
	}

	/**
	 * Gets the absolute path to the root (the parent package, plugin, or repo using this package) directory.
	 *
	 * @since 1.0
	 *
	 * @param string $root The given starting root, if any.
	 *
	 * @return string absolute path to the root directory.
	 */
	protected static function getRootDir( $root ) {
		if ( false === $root ) {
			return dirname( dirname( dirname( __DIR__ ) ) );
		}

		if ( '.' === $root['WPMEDIA_PHPUNIT_ROOT_DIR'] ) {
			return __DIR__;
		}

		return ltrim( $root['WPMEDIA_PHPUNIT_ROOT_DIR'], '/\\' );
	}
}
