<?php

namespace WPMedia\PHPUnit\Integration;

trait ApiTrait {
	protected static $path_to_config;

	protected static function pathToApiCredentialsConfigFile( $path ) {
		self::$path_to_config = $path;
	}

	/**
	 * Gets the credential's value from either an environment variable (stored locally on the machine or CI) or from a local constant defined in `tests/env/local/cloudflare.php`.
	 *
	 * @param string $name Name of the environment variable or constant to find.
	 *
	 * @return string returns the value if available; else an empty string.
	 */
	protected static function getApiCredential( $name ) {
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

		return rocket_get_constant( $name, '' );
	}
}
