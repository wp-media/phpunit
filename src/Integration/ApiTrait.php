<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Integration;

trait ApiTrait {

	/**
	 * Path to the directory holding the local API credentials config file.
	 *
	 * @var string|null
	 */
	protected static $path_to_config;

	/**
	 * Sets the path to the directory holding the local API credentials config file.
	 *
	 * @param string $path Path to the directory holding the config file.
	 *
	 * @return void
	 */
	protected static function pathToApiCredentialsConfigFile( $path ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		self::$path_to_config = $path;
	}

	/**
	 * Gets the credential's value from either an environment variable (stored locally on the machine or CI) or from a local constant defined in `tests/env/local/cloudflare.php`.
	 *
	 * @param string $name Name of the environment variable or constant to find.
	 *
	 * @return string returns the value if available; else an empty string.
	 */
	protected static function getApiCredential( $name ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		$var = getenv( $name );
		if ( ! empty( $var ) ) {
			return $var;
		}

		if ( ! static::$api_credentials_config_file ) {
			return '';
		}

		$config_file = self::$path_to_config . static::$api_credentials_config_file;
		if ( ! is_readable( $config_file ) ) {
			return '';
		}

		// This file is local to the developer's machine and not stored in the repo.
		require_once $config_file;

		if ( ! defined( $name ) ) {
			return '';
		}

		return constant( $name );
	}
}
