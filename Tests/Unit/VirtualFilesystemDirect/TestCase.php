<?php

namespace WPMedia\PHPUnit\Tests\Unit\VirtualFilesystemDirect;

use WPMedia\PHPUnit\Unit\VirtualFilesystemTestCase;

abstract class TestCase extends VirtualFilesystemTestCase {
	protected $path_to_test_data = 'structure.php';

	public function getPathToFixturesDir() {
		return WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/';
	}

	protected function loadTestData( $file ) {
		return $this->getTestData( WPMEDIA_PHPUNIT_ROOT_DIR . '/Tests/Fixtures/', basename( $file, '.php' ) );
	}

	/**
	 * Copied directly from WP_Filesystem_Direct.
	 */
	protected function dirlist( $path, $include_hidden = true, $recursive = false ) {
		if ( $this->filesystem->is_file( $path ) ) {
			$limit_file = basename( $path );
			$path       = dirname( $path );
		} else {
			$limit_file = false;
		}

		if ( ! $this->filesystem->is_dir( $path ) || ! $this->filesystem->is_readable( $path ) ) {
			return false;
		}

		$dir = dir( $path );
		if ( ! $dir ) {
			return false;
		}

		$ret = [];

		while ( false !== ( $entry = $dir->read() ) ) {
			$struc         = [];
			$struc['name'] = $entry;

			if ( '.' == $struc['name'] || '..' == $struc['name'] ) {
				continue;
			}

			if ( ! $include_hidden && '.' == $struc['name'][0] ) {
				continue;
			}

			if ( $limit_file && $struc['name'] != $limit_file ) {
				continue;
			}

			$struc['perms']       = $this->gethchmod( $path . '/' . $entry );
			$struc['permsn']      = $this->filesystem->getnumchmodfromh( $struc['perms'] );
			$struc['number']      = false;
			$struc['owner']       = $this->filesystem->owner( $path . '/' . $entry );
			$struc['group']       = $this->filesystem->group( $path . '/' . $entry );
			$struc['size']        = $this->filesystem->size( $path . '/' . $entry );
			$struc['lastmodunix'] = $this->filesystem->mtime( $path . '/' . $entry );
			$struc['lastmod']     = gmdate( 'M j', $struc['lastmodunix'] );
			$struc['time']        = gmdate( 'h:i:s', $struc['lastmodunix'] );
			$struc['type']        = $this->filesystem->is_dir( $path . '/' . $entry ) ? 'd' : 'f';

			if ( 'd' == $struc['type'] ) {
				if ( $recursive ) {
					$struc['files'] = $this->dirlist( $path . '/' . $struc['name'], $include_hidden, $recursive );
				} else {
					$struc['files'] = [];
				}
			}

			$ret[ $struc['name'] ] = $struc;
		}
		$dir->close();
		unset( $dir );
		return $ret;
	}

	/**
	 * Copied directly from WP_Filesystem_Direct.
	 */
	protected function gethchmod( $file ) {
		$perms = intval( $this->getchmod( $file ), 8 );
		if ( ( $perms & 0xC000 ) == 0xC000 ) { // Socket
			$info = 's';
		} elseif ( ( $perms & 0xA000 ) == 0xA000 ) { // Symbolic Link
			$info = 'l';
		} elseif ( ( $perms & 0x8000 ) == 0x8000 ) { // Regular
			$info = '-';
		} elseif ( ( $perms & 0x6000 ) == 0x6000 ) { // Block special
			$info = 'b';
		} elseif ( ( $perms & 0x4000 ) == 0x4000 ) { // Directory
			$info = 'd';
		} elseif ( ( $perms & 0x2000 ) == 0x2000 ) { // Character special
			$info = 'c';
		} elseif ( ( $perms & 0x1000 ) == 0x1000 ) { // FIFO pipe
			$info = 'p';
		} else { // Unknown
			$info = 'u';
		}

		// Owner
		$info .= ( ( $perms & 0x0100 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0080 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0040 ) ?
			( ( $perms & 0x0800 ) ? 's' : 'x' ) :
			( ( $perms & 0x0800 ) ? 'S' : '-' ) );

		// Group
		$info .= ( ( $perms & 0x0020 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0010 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0008 ) ?
			( ( $perms & 0x0400 ) ? 's' : 'x' ) :
			( ( $perms & 0x0400 ) ? 'S' : '-' ) );

		// World
		$info .= ( ( $perms & 0x0004 ) ? 'r' : '-' );
		$info .= ( ( $perms & 0x0002 ) ? 'w' : '-' );
		$info .= ( ( $perms & 0x0001 ) ?
			( ( $perms & 0x0200 ) ? 't' : 'x' ) :
			( ( $perms & 0x0200 ) ? 'T' : '-' ) );
		return $info;
	}

	/**
	 * Copied directly from WP_Filesystem_Direct.
	 */
	protected function getchmod( $file ) {
		return substr( decoct( @fileperms( $file ) ), -3 );
	}
}
