<?php

namespace WPMedia\PHPUnit\Integration;

/**
 * RESTful Virtual Filesystem Test Case.
 */
abstract class RESTVfsTestCase extends VirtualFilesystemTestCase {
	use RESTTrait;

	protected function set_up() {
		parent::set_up();

		$this->setUpServer();
	}
}
