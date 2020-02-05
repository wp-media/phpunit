<?php

if ( ! function_exists( 'rocket_has_constant' ) ) {
	/**
	 * Checks if the constant is defined.
	 *
	 * @param string $constant_name Name of the constant to check.
	 *
	 * @return bool true when constant is defined; else, false.
	 */
	function rocket_has_constant( $constant_name ) {
		return defined( $constant_name );
	}
}

if ( ! function_exists( 'rocket_get_constant' ) ) {
	/**
	 * Gets the constant is defined.
	 *
	 * @param string $constant_name Name of the constant to check.
	 * @param mixed|null $default Optional. Default value to return if constant is not defined.
	 *
	 * @return bool true when constant is defined; else, false.
	 */
	function rocket_get_constant( $constant_name, $default = null ) {
		if ( ! rocket_has_constant( $constant_name ) ) {
			return $default;
		}

		return constant( $constant_name );
	}
}
