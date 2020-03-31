<?php

namespace WPMedia\PHPUnit\Tests\Integration\VirtualFilesystemDirect;

/**
 * @covers WPMedia\PHPUnit\Tests\Integration\VirtualFilesystemDirect::dirlist
 * @group  VirtualFilesystemDirect
 */
class Test_DirList extends TestCase {
	protected $path_to_test_data = 'dirlist.php';

	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldReturnDirListing( $path, $expected ) {
		$expected = $this->prepListing( $path, $expected );
		$this->assertSame( $expected, $this->filesystem->dirlist( $path ) );
	}

	private function prepListing( $path, $entries ) {
		$path = rtrim( $path, '\//' );
		foreach ( $entries as $entry => $info ) {
			$pathentry           = "{$path}/{$entry}";
			$info['owner']       = $this->filesystem->owner( $pathentry );
			$info['group']       = $this->filesystem->group( $pathentry );
			$info['lastmodunix'] = $this->filesystem->mtime( $pathentry );
			$info['lastmod']     = gmdate( 'M j', $info['lastmodunix'] );
			$info['time']        = gmdate( 'h:i:s', $info['lastmodunix'] );

			$entries[ $entry ] = $info;
		}

		return $entries;
	}
}
