<?php

namespace WPMedia\PHPUnit\Unit;

use Brains\Monkey\Functions;
use WPMedia\PHPUnit\ArrayTrait;
use WPMedia\PHPUnit\VirtualFilesystemDirect;

abstract class VirtualFilesystemTestCase extends TestCase {

	use ArrayTrait;

	/**
	 * Path to the Fixtures directory.
	 *
	 * @var string
	 */
	protected static $path_to_fixtures_dir;

	/**
	 * Path to the config and test data in the Fixtures directory.
	 * Set this path in each test class.
	 *
	 * @var string
	 */
	protected $path_to_test_data;

	/**
	 * The root virtual directory name.
	 *
	 * @var string
	 */
	protected $rootVirtualDir = 'public';

	/**
	 * Virtual directory and file permissions.
	 *
	 * @var int
	 */
	protected $permissions = 0777;

	/**
	 * Do not overwrite these properties.
	 */

	/**
	 * Instance of the virtual filesystem.
	 *
	 * @var VirtualFilesystemDirect
	 */
	protected $filesystem;

	/**
	 * Overwrite with the structure for this test. Gets merged with the default structure.
	 *
	 * @var array
	 */
	protected $structure = [];

	/**
	 * URL to the root directory of the virtual filesystem.
	 *
	 * @var string
	 */
	protected $rootVirtualUrl;

	/**
	 * Structure + test data configuration.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Virtual filestructure for this test, i.e. default merged with configured.
	 *
	 * @var array
	 */
	private $merged_structure = [];

	/**
	 * Original virtual files with flattened full paths.
	 *
	 * @var array
	 */
	protected $original_files = [];

	/**
	 * Original virtual directories with flattened full paths.
	 *
	 * @var array
	 */
	protected $original_dirs = [];

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		if ( empty( $this->config ) ) {
			$this->loadConfig();
		}

		$vfs                  = ArrayTrait::get( $this->config['structure'], rtrim( $this->config['vfs_dir'], '\//' ), [], '/' );
		$this->original_files = array_keys( ArrayTrait::flatten( $vfs, $this->config['vfs_dir'] ) );
		$this->original_dirs  = array_keys( ArrayTrait::flatten( $vfs, $this->config['vfs_dir'], true ) );

		$this->filesystem     = new VirtualFilesystemDirect( $this->rootVirtualDir, $this->mergeStructure(), $this->permissions );
		$this->rootVirtualUrl = $this->filesystem->getUrl( '/' );

		parent::setUp();
	}

	/**
	 * Test Data Provider that uses the `'test_data'` in the config file.
	 *
	 * @return mixed
	 */
	public function providerTestData() {
		$this->loadConfig();

		return $this->config['test_data'];
	}

	/**
	 * Loads the configuration for the vfs structure and test data.
	 */
	protected function loadConfig() {
		$path         = ! empty( static::$path_to_fixtures_dir )
			? static::$path_to_fixtures_dir . $this->path_to_test_data
			: $this->path_to_test_data;
		$this->config = require $path;
	}

	/**
	 * Merges the configured and default virtual filesystem structures.
	 *
	 * @return array merged structure
	 */
	protected function mergeStructure() {
		// If already merged, return it.
		if ( ! empty( $this->merged_structure ) ) {
			return $this->merged_structure;
		}

		$this->structure        = $this->config['structure'];
		$this->merged_structure = array_replace_recursive( $this->getDefaultVfs(), $this->config['structure'] );

		return $this->merged_structure;
	}

	/**
	 * Gets the default virtual directory filesystem structure.
	 *
	 * @return array default structure.
	 */
	private function getDefaultVfs() {
		return [
			'Tests' => [
				'Integration' => [],
				'Unit'        => [],
			],
		];
	}
}
