<?php

namespace WPMedia\PHPUnit;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContent;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 * Virtual filesystem (emulates WP_Filesystem_Direct) using vfsStream.
 *
 * @since 1.1
 */
class VirtualFilesystemDirect {

	/**
	 * Root filesystem directory.
	 *
	 * @var string
	 */
	protected $root;

	/**
	 * Instance of the virtual filesystem.
	 *
	 * @var vfsStreamDirectory
	 */
	protected $filesystem;

	/**
	 * Create an instance of the virtual filesystem.
	 *
	 * @since 1.1
	 *
	 * @param string $rootDirname Optional. Name of the root filesystem directory.
	 * @param array  $structure   Optional. Starting filesystem structure for the root directory.
	 * @param int    $permissions Optional. File permissions for the root directory.
	 */
	public function __construct( $rootDirname = 'cache', array $structure = [], $permissions = 0755 ) {
		$this->root       = trailingslashit( $rootDirname );
		$this->filesystem = vfsStream::setup( $rootDirname, $permissions, $structure );
	}

	/**
	 * Gets the file instance.
	 *
	 * @since 1.1
	 *
	 * @param string $filename Absolute path to file.
	 *
	 * @return vfsStreamContent instance of the file.
	 */
	public function getFile( $filename ) {
		// TODO
	}

	/**
	 * Sets the last modified attribute for the given file.
	 *
	 * @since 1.1
	 *
	 * @param string     $filename Absolute path to file.
	 * @param string|int $filectime
	 *
	 * @return int file's filectime.
	 */
	public function setFilemtime( $filename, $filectime ) {
		// TODO
	}

	/**
	 * Gets the contents of the given file.
	 *
	 * @since 1.1
	 *
	 * @param string $filename Absolute path to file.
	 *
	 * @return string|false file contents string on success; false on failure.
	 */
	public function get_contents( $filename ) {
		// TODO
	}

	/**
	 * Writes a string to a file.
	 *
	 * @since 1.1
	 *
	 * @param string    $filename Absolute path to file.
	 * @param string    $contents The data to write.
	 * @param int|false $mode     Optional. The file permissions as octal number, usually 0644. Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function put_contents( $file, $contents, $mode = false ) {
		// TODO
	}

	/**
	 * Changes filesystem permissions.
	 *
	 * @since 1.1
	 *
	 * @param string    $file      Path to the file.
	 * @param int|false $mode      Optional. The permissions as octal number, usually 0644 for files,
	 *                             0755 for directories. Default false.
	 * @param bool      $recursive Optional. If set to true, changes file group recursively.
	 *                             Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function chmod( $file, $mode = false, $recursive = false ) {
		// TODO
	}

	/**
	 * Deletes a file or directory.
	 *
	 * @since 1.1
	 *
	 * @param string       $file      Path to the file or directory.
	 * @param bool         $recursive Optional. If set to true, changes file group recursively. Default false.
	 * @param string|false $type      Optional. Type of resource. 'f' for file, 'd' for directory. Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete( $file, $recursive = false, $type = false ) {
		// TODO
	}

	/**
	 * Checks if a file or directory exists.
	 *
	 * @since 1.1
	 *
	 * @param string $file Path to file or directory.
	 *
	 * @return bool Whether $file exists or not.
	 */
	public function exists( $file ) {
		// TODO
	}

	/**
	 * Checks if resource is a directory.
	 *
	 * @since 1.1
	 *
	 * @param string $path Directory path.
	 *
	 * @return bool Whether $path is a directory.
	 */
	public function is_dir( $path ) {
		// TODO
	}

	/**
	 * Checks if resource is a file.
	 *
	 * @since 1.1
	 *
	 * @param string $file File path.
	 *
	 * @return bool Whether $file is a file.
	 */
	public function is_file( $file ) {
		// TODO
	}

	/**
	 * Checks if a file is readable.
	 *
	 * @since 1.1
	 *
	 * @param string $file Path to file.
	 *
	 * @return bool Whether $file is readable.
	 */
	public function is_readable( $file ) {
		// TODO
	}

	/**
	 * Checks if a file or directory is writable.
	 *
	 * @since 1.1
	 *
	 * @param string $file Path to file or directory.
	 *
	 * @return bool Whether $file is writable.
	 */
	public function is_writable( $file ) {
		// TODO
	}

	/**
	 * Creates a directory.
	 *
	 * @since 1.1
	 *
	 * @param string    $path  Path for new directory.
	 * @param int|false $chmod Optional. The permissions as octal number (or false to skip chmod). Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function mkdir( $path, $chmod = false ) {
		// TODO
	}

	/**
	 * Deletes a directory.
	 *
	 * @since 1.1
	 *
	 * @param string $path      Path to directory.
	 * @param bool   $recursive Optional. Whether to recursively remove files/directories.
	 *                          Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function rmdir( $path, $recursive = false ) {
		// TODO
	}

	/**
	 * Sets the access and modification times of a file.
	 *
	 * Note: If $file doesn't exist, it will be created.
	 *
	 * @since 1.1
	 *
	 * @param string $file  Path to file.
	 * @param int    $time  Optional. Modified time to set for file.
	 *                      Default 0.
	 * @param int    $atime Optional. Access time to set for file.
	 *                      Default 0.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function touch( $file, $time = 0, $atime = 0 ) {
		// TODO
	}
}
