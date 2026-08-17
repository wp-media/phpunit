<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\ApiTrait;

use WPMedia\PHPUnit\Integration\ApiTrait;

/**
 * Exposes the otherwise protected members of ApiTrait for the tests to drive.
 */
class ApiTraitTestDouble {
	use ApiTrait;

	/**
	 * Name of the API credentials config file, if applicable.
	 *
	 * @var string|null
	 */
	protected static $api_credentials_config_file;

	/**
	 * Points the trait at the config file to load for the current scenario.
	 *
	 * @param string $path_to_config Path to the directory holding the config file.
	 * @param string $config_file    Name of the config file.
	 *
	 * @return void
	 */
	public static function set_config_file( $path_to_config, $config_file ) {
		self::pathToApiCredentialsConfigFile( $path_to_config );

		static::$api_credentials_config_file = $config_file;
	}

	/**
	 * Gets the credential's value for the given name.
	 *
	 * @param string $name Name of the environment variable or constant to find.
	 *
	 * @return string returns the value if available; else an empty string.
	 */
	public static function get_credential( $name ) {
		return static::getApiCredential( $name );
	}

	/**
	 * Resets the trait's static state so each scenario starts from a clean slate.
	 *
	 * @return void
	 */
	public static function reset() {
		self::$path_to_config                = null;
		static::$api_credentials_config_file = null;
	}
}
