<?php

namespace WPMedia\PHPUnit\Integration;

/**
 * RESTful Virtual Filesystem Test Case.
 */
abstract class RESTVfsTestCase extends VirtualFilesystemTestCase {
	use RESTTrait;

	public function setUp() {
		parent::setUp();

		$this->setUpServer();
	}
}
