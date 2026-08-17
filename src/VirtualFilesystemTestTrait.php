<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit;

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
	protected $rootVirtualUrl; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase -- Public API property; renaming would be a breaking change for consumers.

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
	 * Overwrite in the test class to skip running the "initOriginals()" method.
	 *
	 * @var bool
	 */
	protected $skip_initOriginals = false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase -- Public API property; renaming would be a breaking change for consumers.

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
		$this->initOriginals();

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Public API property; renaming would be a breaking change for consumers.
		$this->filesystem     = new VirtualFilesystemDirect( $this->rootVirtualDir, $this->mergeStructure(), $this->permissions );
		$this->rootVirtualUrl = $this->filesystem->getUrl( '/' ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Public API property; renaming would be a breaking change for consumers.
	}

	/**
	 * Test Data Provider that uses the `'test_data'` in the config file.
	 *
	 * @return mixed
	 */
	public function providerTestData() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		$this->loadConfig();

		return $this->config['test_data'];
	}

	/**
	 * Loads the configuration for the vfs structure and test data.
	 */
	protected function loadConfig() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		$this->config = array_merge(
			[
				'vfs_dir'   => '',
				'structure' => [],
				'test_data' => [],
			],
			require $this->getPathToFixturesDir() . $this->path_to_test_data
		);
	}

	/**
	 * Overload in your test case with the path to the Fixtures directory.
	 *
	 * @return string
	 */
	public function getPathToFixturesDir() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		return '';
	}

	/**
	 * Merges the configured and default virtual filesystem structures.
	 *
	 * @return array merged structure
	 */
	protected function mergeStructure() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
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
	 * This is the single source of truth for the default structure used by
	 * {@see mergeStructure()}. Consumers that need a different default structure
	 * should override this method rather than defining a competing default
	 * elsewhere in the class hierarchy.
	 *
	 * @return array default structure.
	 */
	public function getDefaultVfs() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method and the package's documented single override point (issue #36); renaming would be a breaking change for consumers.
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

	/**
	 * Initializes the original files and directories properties for use in the tests.
	 */
	protected function initOriginals() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid -- Public API method; renaming would be a breaking change for consumers.
		// Bail out when "skip_initOriginals" is set to true.
		if ( $this->skip_initOriginals ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- Public API property; renaming would be a breaking change for consumers.
			return;
		}

		if ( ! empty( $this->config['vfs_dir'] ) && '/' !== $this->config['vfs_dir'] ) {
			$vfs_dir   = rtrim( $this->config['vfs_dir'], '/\\' ); // Remove trailing slash for the get.
			$structure = $this->get( $this->config['structure'], $vfs_dir, [], '/' );
			$vfs_dir  .= '/'; // Add the trailing slash for the flattening.
		} else {
			$vfs_dir   = '';
			$structure = $this->config['structure'];
		}

		$this->original_files = array_keys( $this->flatten( $structure, $vfs_dir ) );
		$this->original_dirs  = array_keys( $this->flatten( $structure, $vfs_dir, true ) );
	}
}
