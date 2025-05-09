<?php

namespace WPMedia\PHPUnit\Integration;

/**
 * RESTful Virtual Filesystem Test Case.
 */
abstract class RESTVfsTestCase extends VirtualFilesystemTestCase {
	use RESTTrait;

	public function set_up() {
		parent::set_up();

		$this->setUpServer();
	}
}
