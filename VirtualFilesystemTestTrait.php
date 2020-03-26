<?php

namespace WPMedia\PHPUnit;

use Brains\Monkey\Functions;

trait VirtualFilesystemTestTrait {

	use ArrayTrait;

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
	 * Initializes the test environment.
	 */
	public function init() {
		if ( empty( $this->config ) ) {
			$this->loadConfig();
		}

		$vfs                  = ArrayTrait::get( $this->config['structure'], rtrim( $this->config['vfs_dir'], '\//' ), [], '/' );
		$this->original_files = array_keys( ArrayTrait::flatten( $vfs, $this->config['vfs_dir'] ) );
		$this->original_dirs  = array_keys( ArrayTrait::flatten( $vfs, $this->config['vfs_dir'], true ) );

		$this->filesystem     = new VirtualFilesystemDirect( $this->rootVirtualDir, $this->mergeStructure(), $this->permissions );
		$this->rootVirtualUrl = $this->filesystem->getUrl( '/' );
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
		$this->config = require $this->getPathToFixturesDir() . $this->path_to_test_data;
	}

	/**
	 * Overload in your test case with the path to the Fixtures directory.
	 *
	 * @return string
	 */
	public function getPathToFixturesDir() {
		return '';
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
	public function getDefaultVfs() {
		return [
			'wp-admin'      => [],
			'wp-content'    => [
				'mu-plugins' => [],
				'plugins'    => [
					'wp-rocket' => [],
				],
				'themes'     => [
					'twentytwenty' => [],
				],
				'uploads'    => [],
			],
			'wp-includes'   => [],
			'wp-config.php' => '',
		];
	}
}