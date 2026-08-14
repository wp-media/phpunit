<?php

namespace WPMedia\PHPUnit\Tests\Unit;

use WPMedia\PHPUnit\Integration\ApiTrait;
use WPMedia\PHPUnit\Unit\TestCase;

/**
 * Exposes the otherwise protected members of ApiTrait for the tests to drive.
 */
class ApiTraitTestDouble {
	use ApiTrait;

	protected static $api_credentials_config_file;

	public static function setConfigFile( $path_to_config, $config_file ) {
		self::pathToApiCredentialsConfigFile( $path_to_config );

		static::$api_credentials_config_file = $config_file;
	}

	public static function getCredential( $name ) {
		return static::getApiCredential( $name );
	}
}

/**
 * @covers WPMedia\PHPUnit\Integration\ApiTrait::getApiCredential
 * @group  ApiTrait
 */
class Test_ApiTrait extends TestCase {

	private $tmp_dir;

	protected function set_up() {
		parent::set_up();

		$this->tmp_dir = sys_get_temp_dir() . '/wpmedia-phpunit-apitrait-' . uniqid();

		mkdir( $this->tmp_dir );
	}

	protected function tear_down() {
		array_map( 'unlink', glob( "{$this->tmp_dir}/*.php" ) );
		rmdir( $this->tmp_dir );

		parent::tear_down();
	}

	public function testShouldReturnEnvironmentVariableWhenSet() {
		putenv( 'WPMEDIA_PHPUNIT_TEST_CREDENTIAL=from_env' );

		$this->assertSame( 'from_env', ApiTraitTestDouble::getCredential( 'WPMEDIA_PHPUNIT_TEST_CREDENTIAL' ) );

		putenv( 'WPMEDIA_PHPUNIT_TEST_CREDENTIAL' );
	}

	public function testShouldReturnEmptyStringWhenNoConfigFileIsSet() {
		$this->assertSame( '', ApiTraitTestDouble::getCredential( 'WPMEDIA_PHPUNIT_TEST_UNSET_CONSTANT' ) );
	}

	public function testShouldReturnEmptyStringWhenConfigFileIsNotReadable() {
		ApiTraitTestDouble::setConfigFile( $this->tmp_dir . '/', 'missing-credentials.php' );

		$this->assertSame( '', ApiTraitTestDouble::getCredential( 'WPMEDIA_PHPUNIT_TEST_UNSET_CONSTANT' ) );
	}

	public function testShouldReturnConstantValueDefinedInConfigFile() {
		file_put_contents( $this->tmp_dir . '/credentials.php', "<?php define( 'WPMEDIA_PHPUNIT_TEST_CONSTANT', 'from_constant' );" );

		ApiTraitTestDouble::setConfigFile( $this->tmp_dir . '/', 'credentials.php' );

		$this->assertSame( 'from_constant', ApiTraitTestDouble::getCredential( 'WPMEDIA_PHPUNIT_TEST_CONSTANT' ) );
	}

	public function testShouldReturnEmptyStringWhenConstantIsNotDefinedInConfigFile() {
		file_put_contents( $this->tmp_dir . '/empty-credentials.php', '<?php // No constants defined here.' );

		ApiTraitTestDouble::setConfigFile( $this->tmp_dir . '/', 'empty-credentials.php' );

		$this->assertSame( '', ApiTraitTestDouble::getCredential( 'WPMEDIA_PHPUNIT_TEST_UNDEFINED_CONSTANT' ) );
	}
}
