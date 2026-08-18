<?php

declare(strict_types=1);

namespace WPMedia\PHPUnit\Tests\Unit\TestCaseTrait;

use WPMedia\PHPUnit\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
}

/**
 * Concrete class declared in this file on purpose: {@see \WPMedia\PHPUnit\TestCaseTrait::loadTestDataConfig()}
 * resolves the fixture path from the class's own file location, and no `TestCase.php` fixture exists under
 * `Tests/Fixtures/TestCaseTrait/`. Used by `Test_LoadTestDataConfig` to cover the "no matching fixture" case.
 */
class NoFixtureTestCase extends TestCase {
}
