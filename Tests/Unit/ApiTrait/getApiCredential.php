<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\ApiTrait;

use WPMedia\PHPUnit\Unit\TestCase;

/**
 * Tests WPMedia\PHPUnit\Integration\ApiTrait::getApiCredential().
 *
 * @covers WPMedia\PHPUnit\Integration\ApiTrait::getApiCredential
 * @group  ApiTrait
 */
class Test_GetApiCredential extends TestCase {

	/**
	 * Path to the temporary directory used to hold the config file for the scenarios that need one.
	 *
	 * @var string
	 */
	private $tmp_dir;

	/**
	 * Resets the test double and prepares a scratch directory before each scenario.
	 *
	 * @return void
	 */
	protected function set_up() {
		parent::set_up();

		ApiTraitTestDouble::reset();

		$this->tmp_dir = sys_get_temp_dir() . '/wpmedia-phpunit-apitrait-' . uniqid();

		mkdir( $this->tmp_dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- WP_Filesystem is not available in the Unit test suite; this creates a throwaway scratch directory.
	}

	/**
	 * Removes the scratch directory after each scenario.
	 *
	 * @return void
	 */
	protected function tear_down() {
		array_map( 'unlink', glob( "{$this->tmp_dir}/*.php" ) );
		rmdir( $this->tmp_dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir -- WP_Filesystem is not available in the Unit test suite; this removes the throwaway scratch directory.

		parent::tear_down();
	}

	/**
	 * Asserts that getApiCredential() returns the expected value for each scenario.
	 *
	 * @dataProvider getApiCredentialDataProvider
	 *
	 * @param string|null $env_name             Name of the environment variable to set before running the scenario, if any.
	 * @param string|null $env_value            Value to set the environment variable to, if any.
	 * @param string|null $config_filename      Name of the config file to point the trait at, if any.
	 * @param string|null $config_file_contents Contents to write to the config file before running the scenario, if any.
	 * @param string      $credential_name      Name of the environment variable or constant to look up.
	 * @param string      $expected             Expected return value.
	 *
	 * @return void
	 */
	public function testShouldReturnTheExpectedCredential( $env_name, $env_value, $config_filename, $config_file_contents, $credential_name, $expected ) {
		if ( null !== $env_name ) {
			putenv( "{$env_name}={$env_value}" ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv -- Test-only: simulates the environment variable that getApiCredential() reads via getenv().
		}

		if ( null !== $config_filename ) {
			if ( null !== $config_file_contents ) {
				file_put_contents( $this->tmp_dir . '/' . $config_filename, $config_file_contents ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- WP_Filesystem is not available in the Unit test suite; this writes the throwaway config file fixture.
			}

			ApiTraitTestDouble::set_config_file( $this->tmp_dir . '/', $config_filename );
		}

		$this->assertSame( $expected, ApiTraitTestDouble::get_credential( $credential_name ) );

		if ( null !== $env_name ) {
			putenv( $env_name ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv -- Test-only: unsets the environment variable set above.
		}
	}

	/**
	 * Provides the scenarios from the Fixtures directory.
	 *
	 * @return array test data.
	 */
	public function getApiCredentialDataProvider() {
		return $this->getTestData( __DIR__, 'getApiCredential' );
	}
}
