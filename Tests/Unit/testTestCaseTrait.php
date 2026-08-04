<?php

namespace WPMedia\PHPUnit\Tests\Unit;

use WPMedia\PHPUnit\Unit\TestCase;

/**
 * Target class exposing non-public members for the reflection helpers to operate on.
 */
class ReflectionTarget {

	private $instance_property = 'initial';

	private static $static_property = 'initial static';

	private function secret( $suffix ) {
		return "secret {$suffix}";
	}

	public function get_instance_property() {
		return $this->instance_property;
	}

	public static function get_static_property() {
		return self::$static_property;
	}

	public static function reset() {
		self::$static_property = 'initial static';
	}
}

/**
 * @covers WPMedia\PHPUnit\TestCaseTrait::get_reflective_method
 * @covers WPMedia\PHPUnit\TestCaseTrait::get_reflective_property
 * @covers WPMedia\PHPUnit\TestCaseTrait::set_reflective_property
 * @covers WPMedia\PHPUnit\TestCaseTrait::getNonPublicPropertyValue
 * @group  TestCaseTrait
 */
class Test_TestCaseTrait extends TestCase {

	protected function tear_down() {
		ReflectionTarget::reset();

		parent::tear_down();
	}

	public function testShouldSetAndGetInstancePropertyWhenGivenAnInstance() {
		$target = new ReflectionTarget();

		$this->set_reflective_property( 'changed', 'instance_property', $target );

		$this->assertSame( 'changed', $target->get_instance_property() );
		$this->assertSame( 'changed', $this->getNonPublicPropertyValue( 'instance_property', ReflectionTarget::class, $target ) );
	}

	public function testShouldSetAndGetStaticPropertyWhenGivenAClassName() {
		$this->set_reflective_property( 'changed static', 'static_property', ReflectionTarget::class );

		$this->assertSame( 'changed static', ReflectionTarget::get_static_property() );
		$this->assertSame( 'changed static', $this->getNonPublicPropertyValue( 'static_property', ReflectionTarget::class ) );
	}

	public function testShouldSetAndGetStaticPropertyWhenGivenAnInstance() {
		$target = new ReflectionTarget();

		$this->set_reflective_property( 'changed static', 'static_property', $target );

		$this->assertSame( 'changed static', ReflectionTarget::get_static_property() );
		$this->assertSame( 'changed static', $this->getNonPublicPropertyValue( 'static_property', ReflectionTarget::class, $target ) );
	}

	public function testShouldInvokeNonPublicMethod() {
		$method = $this->get_reflective_method( 'secret', ReflectionTarget::class );

		$this->assertSame( 'secret value', $method->invoke( new ReflectionTarget(), 'value' ) );
	}

	/**
	 * The helpers must not emit deprecations on any supported PHP version:
	 * ReflectionProperty::setValue() with a non-object first argument is deprecated as of PHP 8.3, and
	 * Reflection*::setAccessible() is deprecated as of PHP 8.5.
	 *
	 * @see https://github.com/wp-media/phpunit/issues/29
	 */
	public function testShouldNotTriggerDeprecationsOnAnySupportedPhpVersion() {
		$deprecations = [];

		set_error_handler(
			function ( $errno, $errstr ) use ( &$deprecations ) {
				$deprecations[] = $errstr;

				return true;
			},
			E_DEPRECATED | E_USER_DEPRECATED
		);

		try {
			$target = new ReflectionTarget();

			$this->get_reflective_method( 'secret', ReflectionTarget::class );
			$this->get_reflective_property( 'instance_property', $target );
			$this->set_reflective_property( 'changed', 'instance_property', $target );
			$this->set_reflective_property( 'changed static', 'static_property', ReflectionTarget::class );
			$this->getNonPublicPropertyValue( 'static_property', ReflectionTarget::class );
		} finally {
			restore_error_handler();
		}

		$this->assertSame( [], $deprecations );
	}
}
