<?php

namespace WPMedia\PHPUnit;

use FilesystemIterator;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamAbstractContent;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamDirectory;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
	 * @param array  $structure   Optional. Starting filesystem structure for the root directory.
	 * @param int    $permissions Optional. File permissions for the root directory.
	 *
	 * @param string $rootDirname Optional. Name of the root filesystem directory.
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
	 * @param string|int           $filectime Last modification time to set for the file.
	 *
	 * @param string|vfsStreamFile $file      Absolute path to file when string or instance of vfsStreamFile.
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
	 * @param string    $contents The data to write.
	 * @param int|false $mode     Optional. The file permissions as octal number, usually 0644. Default false.
	 *
	 * @param string    $filename Absolute path to file.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function put_contents( $filename, $contents, $mode = false ) {
		$file = $this->getOrCreateFile( $filename );
		if ( is_null( $file ) ) {
			return false;
		}

		$file->setContent( $contents );

		// chmod is the last step in WP_Filesystem_Direct::put_contents().
		$this->chmod( $filename, $mode );

		return true;
	}

	/**
	 * Get file. If doesn't exist, creates it and then returns it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filename Absolute path to file.
	 *
	 * @return bool|vfsStreamFile file on success; false on failure.
	 */
	protected function getOrCreateFile( $filename ) {
		$filename = $this->prefixRoot( $filename );

		// If the file exists, return it.
		if ( $this->is_file( $filename ) ) {
			return $this->getFile( $filename );
		}

		// Attempt to create the file. If it fails, return false.
		if ( ! $this->touch( $filename ) ) {
			return false;
		}

		// Created it. Get and return the file.
		return $this->getFile( $filename );
	}

	/**
	 * Changes filesystem permissions.
	 *
	 * @since 1.1
	 *
	 * @param int|false $mode      Optional. The permissions as octal number, usually 0644 for files,
	 *                             0755 for directories. Default false.
	 * @param bool      $recursive Optional. If set to true, changes file group recursively. Default false.
	 *
	 * @param string    $fileOrDir Path to the file or directory.
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
	 * @param bool         $recursive Optional. If set to true, changes file group recursively. Default false.
	 * @param string|false $type      Optional. Type of resource. 'f' for file, 'd' for directory. Default false.
	 *
	 * @param string       $fileOrDir Path to the file or directory.
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
	 * Gets details for files in a directory or a specific file.
	 *
	 * Copied from WP_Filesystem_Direct.
	 *
	 * @param string $path           Path to directory or file.
	 * @param bool   $include_hidden Optional. Whether to include details of hidden ("." prefixed) files.
	 *                               Default true.
	 * @param bool   $recursive      Optional. Whether to recursively include file details in nested directories.
	 *                               Default false.
	 *
	 * @return array|false Array of files. False if unable to list directory contents.
	 */
	public function dirlist( $path, $include_hidden = true, $recursive = false ) {
		$path = $this->getUrl( $path );

		if ( $this->is_file( $path ) ) {
			$limit_file = basename( $path );
			$path       = dirname( $path );
		} else {
			$limit_file = false;
		}

		if ( ! $this->is_dir( $path ) || ! $this->is_readable( $path ) ) {
			return false;
		}

		$dir = dir( $path );
		if ( ! $dir ) {
			return false;
		}

		$listing = [];

		while ( false !== ( $entry = $dir->read() ) ) {
			$struc         = [
				'name' => $entry,
			];

			if ( '.' === $struc['name'] || '..' === $struc['name'] ) {
				continue;
			}

			if ( ! $include_hidden && '.' === $struc['name'][0] ) {
				continue;
			}

			if ( $limit_file && $struc['name'] !== $limit_file ) {
				continue;
			}

			$path                 = rtrim( $path, '\//' );
			$pathentry            = "{$path}/{$entry}";
			$struc['perms']       = $this->gethchmod( $pathentry );
			$struc['permsn']      = $this->getnumchmodfromh( $struc['perms'] );
			$struc['number']      = false;
			$struc['owner']       = $this->owner( $pathentry );
			$struc['group']       = $this->group( $pathentry );
			$struc['size']        = $this->size( $pathentry );
			$struc['lastmodunix'] = $this->mtime( $pathentry );
			$struc['lastmod']     = gmdate( 'M j', $struc['lastmodunix'] );
			$struc['time']        = gmdate( 'h:i:s', $struc['lastmodunix'] );
			$struc['type']        = $this->is_dir( $pathentry ) ? 'd' : 'f';

			if ( 'd' === $struc['type'] ) {
				$struc['files'] = $recursive
					? $this->dirlist( $path . '/' . $struc['name'], $include_hidden, $recursive )
					: [];
			}

			$listing[ $struc['name'] ] = $struc;
		}
		$dir->close();
		unset( $dir );

		return $listing;
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
	 * Gets the permissions of the specified file or filepath in their octal format.
	 *
	 * Copied from WP_Filesystem_Direct.
	 *
	 * @param string $file Path to the file.
	 *
	 * @return string Mode of the file (the last 3 digits).
	 */
	public function getchmod( $file ) {
		$file = $this->getUrl( $file );
		return substr( decoct( @fileperms( $file ) ), -3 );
	}

	/**
	 * Returns the *nix-style file permissions for a file.
	 *
	 * Copied from WP_Filesystem_Direct.
	 *
	 * @return string The *nix-style representation of permissions.
	 */
	public function gethchmod( $file ) {
		$perms = intval( $this->getchmod( $file ), 8 );

		if ( ( $perms & 0xC000 ) === 0xC000 ) { // Socket
			$info = 's';
		} elseif ( ( $perms & 0xA000 ) === 0xA000 ) { // Symbolic Link
			$info = 'l';
		} elseif ( ( $perms & 0x8000 ) === 0x8000 ) { // Regular
			$info = '-';
		} elseif ( ( $perms & 0x6000 ) === 0x6000 ) { // Block special
			$info = 'b';
		} elseif ( ( $perms & 0x4000 ) === 0x4000 ) { // Directory
			$info = 'd';
		} elseif ( ( $perms & 0x2000 ) === 0x2000 ) { // Character special
			$info = 'c';
		} elseif ( ( $perms & 0x1000 ) === 0x1000 ) { // FIFO pipe
			$info = 'p';
		} else { // Unknown
			$info = 'u';
		}

		// Owner.
		$info .= ( ( $perms & 0x0100 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0080 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0040 )
			? ( ( $perms & 0x0800 ) ? 's' : 'x' )
			: ( ( $perms & 0x0800 ) ? 'S' : '-' ) );

		// Group
		$info .= ( ( $perms & 0x0020 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0010 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0008 )
			? ( ( $perms & 0x0400 ) ? 's' : 'x' )
			: ( ( $perms & 0x0400 ) ? 'S' : '-' ) );

		// World
		$info .= ( ( $perms & 0x0004 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0002 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0001 )
			? ( ( $perms & 0x0200 ) ? 't' : 'x' )
			: ( ( $perms & 0x0200 ) ? 'T' : '-' ) );

		return $info;
	}

	/**
	 * Get the chmod info for owner, group, or world.
	 *
	 * @param string $perms Intval of the file's permissions.
	 * @param string $type  'owner', 'group', or 'world'
	 *
	 * @return string chmod info.
	 */
	private function getChmodInfo( $perms, $type ) {
		if ( 'owner' === $type ) {
			$codes = [
				'r'  => 0x0100,
				'w'  => 0x0080,
				'sS' => 0x0040,
				's'  => 0x0800,
				'S'  => 0x0800,
			];
		} elseif ( 'group' === $type ) {
			$codes = [
				'r'  => 0x0020,
				'w'  => 0x0010,
				'sS' => 0x0008,
				's'  => 0x0400,
				'S'  => 0x0400,
			];
		} elseif ( 'world' === $type ) {
			$codes = [
				'r'  => 0x0020,
				'w'  => 0x0010,
				'sS' => 0x0008,
				's'  => 0x0400,
				'S'  => 0x0400,
			];
		} else {
			return '';
		}

		$info  = ( ( $perms & $codes['r'] ) ? 'r' : '-' );
		$info .= ( ( $perms & $codes['w'] ) ? 'w' : '-' );
		$info .= ( ( $perms & $codes['sS'] )
			? ( ( $perms & $codes['s'] ) ? 's' : 'x' )
			: ( ( $perms & $codes['S'] ) ? 'S' : '-' ) );

		return $info;
	}

	/**
	 * Converts *nix-style file permissions to a octal number.
	 *
	 * Copied from WP_Filesystem_Direct
	 *
	 * @param string $mode string The *nix-style file permission.
	 *
	 * @return int octal representation.
	 */
	public function getnumchmodfromh( $mode ) {
		$realmode = '';
		$legal    = [ '', 'w', 'r', 'x', '-' ];
		$attarray = preg_split( '//', $mode );

		for ( $i = 0, $c = count( $attarray ); $i < $c; $i ++ ) {
			$key = array_search( $attarray[ $i ], $legal );
			if ( $key ) {
				$realmode .= $legal[ $key ];
			}
		}

		$mode  = str_pad( $realmode, 10, '-', STR_PAD_LEFT );
		$trans = [
			'-' => '0',
			'r' => '4',
			'w' => '2',
			'x' => '1',
		];
		$mode  = strtr( $mode, $trans );

		$newmode = $mode[0];
		$newmode .= $mode[1] + $mode[2] + $mode[3];
		$newmode .= $mode[4] + $mode[5] + $mode[6];
		$newmode .= $mode[7] + $mode[8] + $mode[9];

		return $newmode;
	}

	/**
	 * Gets a list of all the directories within the given virtual root directory.
	 *
	 * @param string $dir Virtual directory absolute path.
	 *
	 * @return array of all directories.
	 */
	public function getDirsListing( $dir ) {
		return $this->scanFS( $dir, true );
	}

	/**
	 * Gets a list of all the files within the given virtual root directory.
	 *
	 * @param string $dir Virtual directory absolute path.
	 *
	 * @return array of all files.
	 */
	public function getFilesListing( $dir ) {
		return $this->scanFS( $dir, false, true );
	}

	/**
	 * Gets a list of all the files and directories within the given virtual root directory.
	 *
	 * @param string $dir Virtual directory absolute path.
	 *
	 * @return array of all files and directories.
	 */
	public function getListing( $dir ) {
		return $this->scanFS( $dir );
	}

	/**
	 * Gets the file's group.
	 *
	 * @param string $file Path to the file.
	 *
	 * @return string|false group's name on success; else, false.
	 */
	public function group( $file ) {
		$file = $this->getFile( $file );
		if ( is_null( $file ) ) {
			return false;
		}

		$group_id   = $file->getGroup();
		$group_info = posix_getgrgid( $group_id );

		return $group_info['name'];
	}

	/**
	 * Scans the filesystem and returns a list of files, directories, or both within the given virtual root directory.
	 *
	 * @param string  $dir       Virtual directory absolute path.
	 * @param boolean $dirOnly   Optional. When true, returns only directories.
	 * @param boolean $filesOnly Optional. When true, returns only files.
	 *
	 * @return array of all files, directories, or both.
	 */
	protected function scanFS( $dir, $dirOnly = false, $filesOnly = false ) {
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $this->getUrl( $dir ), FilesystemIterator::SKIP_DOTS ),
			$filesOnly ? RecursiveIteratorIterator::LEAVES_ONLY : RecursiveIteratorIterator::SELF_FIRST
		);

		$items = [];
		foreach ( $iterator as $item ) {
			if ( ! $filesOnly && $item->isDir() ) {
				$items[] = $item->getPathname() . DIRECTORY_SEPARATOR;
			} elseif ( ! $dirOnly ) {
				$items[] = $item->getPathname();
			}
		}

		return $items;
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
	 * @param int|false $chmod Optional. The permissions as octal number (or false to skip chmod). Default false.
	 *
	 * @param string    $path  Path for new directory.
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
	 * Gets the file's last access time.
	 *
	 * @param string $file Path to file.
	 *
	 * @return int|false Unix timestamp representing last access time; else, false.
	 */
	public function atime( $file ) {
		$file = $this->getFile( $file );
		if ( is_null( $file ) ) {
			return false;
		}

		return $file->fileatime();
	}

	/**
	 * Gets the file modification time.
	 *
	 * @param string $file Path to file.
	 *
	 * @return int|false Unix timestamp representing modification time, false on failure.
	 */
	public function mtime( $file ) {
		$file = $this->getFile( $file );
		if ( is_null( $file ) ) {
			return false;
		}

		return $file->filemtime();
	}

	/**
	 * Gets the file owner's username.
	 *
	 * @param string $file Path to the file.
	 *
	 * @return string|false Owner's username on success; else, false.
	 */
	public function owner( $file ) {
		$file = $this->getFile( $file );
		if ( is_null( $file ) ) {
			return false;
		}

		$owner_id   = $file->getUser();
		$owner_info = posix_getpwuid( $owner_id );

		return $owner_info['name'];
	}

	/**
	 * Deletes a directory.
	 *
	 * @since 1.1
	 *
	 * @param bool   $recursive Optional. Whether to recursively remove files/directories. Default false.
	 *
	 * @param string $dir       Path to directory.
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
	 * Gets the file size (in bytes).
	 *
	 * @param string $file Path to file.
	 *
	 * @return int|false Size of the file in bytes on success; else false.
	 */
	public function size( $file ) {
		$file = $this->getFile( $file );
		if ( is_null( $file ) ) {
			return false;
		}

		return $file->size();
	}

	/**
	 * Sets the access and modification times of a file.
	 *
	 * Note: If $file doesn't exist, it will be created.
	 *
	 * @since 1.1
	 *
	 * @param int    $time  Optional. Modified time to set for file.
	 *                      Default 0.
	 * @param int    $atime Optional. Access time to set for file.
	 *                      Default 0.
	 *
	 * @param string $file  Path to file.
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
	 * @param vfsStreamDirectory $child   Instance of the child directory.
	 *
	 * @param string             $dirname Child directory name.
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
		// Strip off the URL.
		$fileOrDir = str_replace( 'vfs://', '', $fileOrDir );

		// Bail out if it already has the root.
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
