<?php

namespace WPMedia\PHPUnit;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamAbstractContent;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 * Virtual filesystem (emulates WP_Filesystem_Direct) using vfsStream.
 *
 * @since 1.1
 */
class VirtualFilesystemDirect {
	use TestCaseTrait;

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
	 * File permissions for the root directory.
	 *
	 * @var int
	 */
	protected $permissions;

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
		$this->root        = rtrim( $rootDirname, '/\\' );
		$this->permissions = $permissions;
		$this->filesystem  = vfsStream::setup( $this->root, $permissions, $structure );
	}

	/**
	 * Gets the file instance.
	 *
	 * @since 1.1
	 *
	 * @param string $filename Absolute path to file.
	 *
	 * @return vfsStreamFile|null instance of the file if exists; else null.
	 */
	public function getFile( $filename ) {
		$filename = $this->prefixRoot( $filename );

		return $this->filesystem->getChild( $filename );
	}

	/**
	 * Gets the directory instance, if it exists.
	 *
	 * @since 1.1
	 *
	 * @param string $dirname Path to the directory.
	 *
	 * @return vfsStreamDirectory|null
	 */
	public function getDir( $dirname ) {
		$dirname = rtrim( $this->prefixRoot( $dirname ), '/\\' );

		if ( $dirname === $this->root ) {
			return $this->filesystem;
		}

		return $this->filesystem->getChild( $dirname );
	}

	/**
	 * Gets the absolute vfsStream::url for the given file or directory.
	 *
	 * @since 1.1
	 *
	 * @param string $fileOrDir Path to the file or directory.
	 *
	 * @return string absolute url.
	 */
	public function getUrl( $fileOrDir ) {
		return vfsStream::url( $this->prefixRoot( $fileOrDir ) );
	}

	/**
	 * Sets the last modified attribute for the given file.
	 *
	 * @since 1.1
	 *
	 * @param string|vfsStreamFile $file      Absolute path to file when string or instance of vfsStreamFile.
	 * @param string|int           $filectime Last modification time to set for the file.
	 *
	 * @return int file's filectime when file exists; else null.
	 */
	public function setFilemtime( $file, $filemtime ) {
		if ( ! is_a( $file, 'org\bovigo\vfs\vfsStreamFile' ) ) {
			$file = $this->getFile( $file );
			if ( is_null( $file ) ) {
				return null;
			}
		}

		if ( is_string( $filemtime ) ) {
			$filemtime = strtotime( $filemtime );
		}
		$file->lastModified( $filemtime );

		return $file->filemtime();
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
		if ( ! $this->exists( $filename ) ) {
			return false;
		}

		return @file_get_contents( $this->getUrl( $filename ) );
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
	public function put_contents( $filename, $contents, $mode = false ) {
		$file = $this->getFile( $filename );
		if ( is_null( $file ) ) {
			return false;
		}

		$file->setContent( $contents );

		// chmod is the last step in WP_Filesystem_Direct::put_contents().
		$this->chmod( $filename, $mode );

		return true;
	}

	/**
	 * Changes filesystem permissions.
	 *
	 * @since 1.1
	 *
	 * @param string    $fileOrDir Path to the file or directory.
	 * @param int|false $mode      Optional. The permissions as octal number, usually 0644 for files,
	 *                             0755 for directories. Default false.
	 * @param bool      $recursive Optional. If set to true, changes file group recursively. Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function chmod( $fileOrDir, $mode = false, $recursive = false ) {
		if ( ! $this->exists( $fileOrDir ) ) {
			return false;
		}

		if ( false === $mode ) {
			if ( $this->is_file( $fileOrDir ) ) {
				$mode = 0644;
			} elseif ( $this->is_dir( $fileOrDir ) ) {
				$mode = 0755;
			} else {
				return false;
			}
		}

		return chmod( $this->getUrl( $fileOrDir ), $mode );
	}

	/**
	 * Deletes a file or directory.
	 *
	 * @since 1.1
	 *
	 * @param string       $fileOrDir Path to the file or directory.
	 * @param bool         $recursive Optional. If set to true, changes file group recursively. Default false.
	 * @param string|false $type      Optional. Type of resource. 'f' for file, 'd' for directory. Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete( $fileOrDir, $recursive = false, $type = false ) {
		$fileOrDir = $this->prefixRoot( $fileOrDir );

		if ( $recursive || 'd' === $type || $this->is_dir( $fileOrDir ) ) {
			return $this->rmdir( $fileOrDir, $recursive );
		}

		if ( ! $this->is_file( $fileOrDir ) ) {
			return false;
		}

		return @unlink( $this->getUrl( $fileOrDir ) );
	}

	/**
	 * Checks if a file or directory exists.
	 *
	 * @since 1.1
	 *
	 * @param string $fileOrDir Path to file or directory.
	 *
	 * @return bool Whether $fileOrDir exists or not.
	 */
	public function exists( $fileOrDir ) {
		if ( ! $this->hasFilesystem() ) {
			return false;
		}

		return (
			$this->is_dir( $fileOrDir )
			||
			$this->is_file( $fileOrDir )
		);
	}

	/**
	 * Checks if resource is a directory.
	 *
	 * @since 1.1
	 *
	 * @param string $dir Directory path.
	 *
	 * @return bool Whether $dir is a directory.
	 */
	public function is_dir( $dir ) {
		if ( ! $this->hasFilesystem() ) {
			return false;
		}

		return ( $this->getDir( $dir ) instanceof vfsStreamDirectory );
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
		if ( ! $this->hasFilesystem() ) {
			return false;
		}

		return ( $this->getFile( $file ) instanceof vfsStreamFile );
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
		return is_writeable( $this->getUrl( $file ) );
	}

	/**
	 * Checks if a file or directory is writable.
	 *
	 * @since 1.1
	 *
	 * @param string $fileOrDir Path to file or directory.
	 *
	 * @return bool true if $file is writable; else, false.
	 */
	public function is_writable( $fileOrDir ) {
		return is_writeable( $this->getUrl( $fileOrDir ) );
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
		if ( ! @mkdir( $this->getUrl( $path ) ) ) {
			return false;
		}

		$this->chmod( $path, $chmod ?: $this->permissions );

		return true;
	}

	/**
	 * Deletes a directory.
	 *
	 * @since 1.1
	 *
	 * @param string $dir       Path to directory.
	 * @param bool   $recursive Optional. Whether to recursively remove files/directories. Default false.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function rmdir( $dir, $recursive = false ) {
		$dir = $this->prefixRoot( $dir );

		if ( ! $this->is_dir( $dir ) ) {
			return false;
		}

		if ( ! $recursive ) {
			return @rmdir( $this->getUrl( $dir ) );
		}

		// At this point it's a folder, and we're in recursive mode.

		// When removing the root, the filesystem is deleted.
		if ( $dir === $this->root ) {
			$this->root       = null;
			$this->filesystem = null;

			return true;
		}

		$child   = $this->getDir( $dir );
		$dirname = $this->getNonPublicPropertyValue( 'name', vfsStreamAbstractContent::class, $child );
		$parent  = $this->getParentDir( $dirname, $child );

		return $parent->removeChild( $dirname );
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
		if ( $time == 0 ) {
			$time = time();
		}
		if ( $atime == 0 ) {
			$atime = time();
		}
		return touch( $this->getUrl( $file ), $time, $atime );
	}

	/**
	 * Gets the parent directory, if it exists.
	 *
	 * @since 1.1
	 *
	 * @param string             $dirname Child directory name.
	 * @param vfsStreamDirectory $child   Instance of the child directory.
	 *
	 * @return vfsStreamDirectory|null parent directory on success; null when no parent directory.
	 */
	protected function getParentDir( $dirname, $child ) {
		$parentPath = $this->getNonPublicPropertyValue( 'parentPath', vfsStreamAbstractContent::class, $child );

		// Directory is root. There's no parent. Bail out.
		if ( is_null( $parentPath ) && $dirname === $this->root ) {
			return null;
		}

		// There's no parent. Bail out.
		if ( is_null( $parentPath ) ) {
			return null;
		}

		return $this->getDir( $parentPath );
	}

	/**
	 * Checks if the filesystem exists.
	 *
	 * @since 1.1
	 *
	 * @return bool
	 */
	protected function hasFilesystem() {
		return (
			$this->filesystem instanceof vfsStreamDirectory
			&&
			is_string( $this->root )
			&&
			'' !== $this->root
		);
	}

	/**
	 * Adds the root if missing.
	 *
	 * @since 1.1
	 *
	 * @param string $fileOrDir Path to file or directory.
	 *
	 * @return string file or directory path with root.
	 */
	protected function prefixRoot( $fileOrDir ) {
		if ( $this->startsWithRoot( $fileOrDir ) ) {
			return $fileOrDir;
		}

		return $this->root . DIRECTORY_SEPARATOR . ltrim( $fileOrDir, '\//' );
	}

	/**
	 * Checks if the given filename starts with the root directory.
	 *
	 * @since 1.1
	 *
	 * @param $filename
	 *
	 * @return bool true when starts with root directory; else false.
	 */
	protected function startsWithRoot( $filename ) {
		return ( substr( $filename, 0, strlen( $this->root ) ) === $this->root );
	}
}
