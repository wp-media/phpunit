<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Unit;

use WPMedia\PHPUnit\TestCaseTrait;
use Yoast\WPTestUtils\BrainMonkey\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	use TestCaseTrait;
}
