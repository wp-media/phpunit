<?php

namespace WPMedia\PHPUnit;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

trait TestCaseTrait {

	protected static function stubPolyfills() {
		require_once __DIR__ . '/Fixtures/polyfills.php';
	}

	/**
	 * Gets the test data, if it exists, for this test class.
	 *
	 * @param string $dir      Directory of the test class.
	 * @param string $filename Test data filename without the .php extension.
	 *
	 * @return array array of test data.
	 */
	protected function getTestData( $dir, $filename ) {
		if ( empty( $dir ) || empty( $filename ) ) {
			return [];
		}

		$dir = str_replace( [ 'Integration', 'Unit' ], 'Fixtures', $dir );
		$dir = rtrim( $dir, '\\/' );
		$testdata = "$dir/{$filename}.php";

		return is_readable( $testdata )
			? require $testdata
			: [];
	}

	/**
	 * Get reflective access to the private/protected method.
	 *
	 * @param string $method_name Method name for which to gain access.
	 * @param string $class_name  Name of the target class.
	 *
	 * @return ReflectionMethod
	 * @throws ReflectionException Throws an exception if method does not exist.
	 */
	protected function get_reflective_method( $method_name, $class_name ) {
		$class  = new ReflectionClass( $class_name );
		$method = $class->getMethod( $method_name );
		$method->setAccessible( true );

		return $method;
	}

	/**
	 * Get reflective access to the private/protected property.
	 *
	 * @param string       $property Property name for which to gain access.
	 * @param string|mixed $class    Class name or instance.
	 *
	 * @return ReflectionProperty|string
	 * @throws ReflectionException Throws an exception if property does not exist.
	 */
	protected function get_reflective_property( $property, $class ) {
		$class    = new ReflectionClass( $class );
		$property = $class->getProperty( $property );
		$property->setAccessible( true );

		return $property;
	}

	/**
	 * Set the value of a property or private property.
	 *
	 * @param mixed  $value    The value to set for the property.
	 * @param string $property Property name for which to gain access.
	 * @param mixed  $instance Instance of the target object.
	 *
	 * @return ReflectionProperty|string
	 * @throws ReflectionException Throws an exception if property does not exist.
	 */
	protected function set_reflective_property( $value, $property, $instance ) {
		$property = $this->get_reflective_property( $property, $instance );
		$property->setValue( $instance, $value );
		$property->setAccessible( false );

		return $property;
	}

	protected function getNonPublicPropertyValue( $property, $class, $instance = null ) {
		$property = $this->get_reflective_property( $property, $class );

		if ( is_null( $instance ) ) {
			return $property->getValue();
		}

		return $property->getValue( $instance );
	}

	/**
	 * Format the HTML by stripping out the whitespace between the HTML tags and then putting each tag on a separate
	 * line.
	 *
	 * Why? We can then compare the actual vs. expected HTML patterns without worrying about tabs, new lines, and extra
	 * spaces.
	 *
	 * @param string $html HTML to strip.
	 *
	 * @return string stripped HTML.
	 */
	protected function format_the_html( $html ) {
		$html = trim( $html );

		// Strip whitespace between the tags.
		$html = preg_replace( '/(\>)\s*(\<)/m', '$1$2', $html );

		// Strip whitespace at the end of a tag.
		$html = preg_replace( '/(\>)\s*/m', '$1$2', $html );

		// Strip whitespace at the start of a tag.
		$html = preg_replace( '/\s*(\<)/m', '$1$2', $html );

		return str_replace( '>', ">\n", $html );
	}
}
