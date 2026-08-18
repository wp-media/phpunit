<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;

trait TestCaseTrait {

	/**
	 * Gets the test data, if it exists, for this test class.
	 *
	 * @param string $dir      Directory of the test class.
	 * @param string $filename Test data filename without the .php extension.
	 *
	 * @return array array of test data.
	 */
	protected function getTestData( $dir, $filename ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		if ( empty( $dir ) || empty( $filename ) ) {
			return [];
		}

		$dir      = str_replace( [ 'Integration', 'Unit' ], 'Fixtures', $dir );
		$dir      = rtrim( $dir, '\\/' );
		$testdata = "$dir/{$filename}.php";

		return is_readable( $testdata )
			? require $testdata
			: [];
	}

	/**
	 * Structure + test data configuration, lazily loaded by {@see configTestData()}.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Test Data Provider that uses the `'test_data'` key of the config file matching the test class,
	 * lazily loading it on first use.
	 *
	 * @return array
	 */
	public function configTestData() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		if ( empty( $this->config ) ) {
			$this->loadTestDataConfig();
		}

		return $this->config['test_data'] ?? $this->config;
	}

	/**
	 * Loads the test data config file matching the current test class name and location.
	 *
	 * @return void
	 */
	protected function loadTestDataConfig() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		$file = ( new ReflectionObject( $this ) )->getFileName();

		$this->config = $this->getTestData( dirname( $file ), basename( $file, '.php' ) );
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

		self::set_reflector_accessible( $method, true );

		return $method;
	}

	/**
	 * Get reflective access to the private/protected property.
	 *
	 * @param string       $property   Property name for which to gain access.
	 * @param string|mixed $class_name Class name or instance.
	 *
	 * @return ReflectionProperty|string
	 * @throws ReflectionException Throws an exception if property does not exist.
	 */
	protected function get_reflective_property( $property, $class_name ) {
		$class    = new ReflectionClass( $class_name );
		$property = $class->getProperty( $property );

		self::set_reflector_accessible( $property, true );

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

		// A static property has no target object: passing anything but null is deprecated as of PHP 8.3.
		if ( $property->isStatic() ) {
			$property->setValue( null, $value );
		} else {
			$property->setValue( $instance, $value );
		}

		self::set_reflector_accessible( $property, false );

		return $property;
	}

	/**
	 * Gets the value of a private/protected property.
	 *
	 * @param string       $property   Property name for which to gain access.
	 * @param string|mixed $class_name Class name or instance.
	 * @param mixed|null   $instance   Instance of the target object, if the property is not static.
	 *
	 * @return mixed the property's value.
	 * @throws ReflectionException Throws an exception if property does not exist.
	 */
	protected function getNonPublicPropertyValue( $property, $class_name, $instance = null ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		$property = $this->get_reflective_property( $property, $class_name );

		if ( is_null( $instance ) || $property->isStatic() ) {
			return $property->getValue();
		}

		return $property->getValue( $instance );
	}

	/**
	 * Toggles accessibility on a reflected method or property.
	 *
	 * ReflectionMethod::setAccessible() and ReflectionProperty::setAccessible() have no effect since PHP 8.1, where
	 * reflection grants access to non-public members by default, and are deprecated as of PHP 8.5. They are still
	 * required on PHP 7.4 and 8.0.
	 *
	 * @param ReflectionMethod|ReflectionProperty $reflector  Reflected method or property.
	 * @param bool                                $accessible Whether to make the member accessible.
	 *
	 * @return void
	 */
	private static function set_reflector_accessible( $reflector, $accessible ) {
		if ( PHP_VERSION_ID >= 80100 ) {
			return;
		}

		$reflector->setAccessible( $accessible );
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
