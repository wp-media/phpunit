# Common PHPUnit Unit and Integration Bootstrapper

This reusable package bootstraps our PHPUnit unit and integration tests. It includes:

- bootstrapping for both Unit and Integration tests
- `phpunit.xml.dist` for each test suite
- `TestCase` for each test suite
- Common polyfill functions

This means your repo only needs its tests. w00t!

## Configuring Composer in Your Repo

In your repo's `composer.json` file, add the following `"require-dev"` dependency:

```json
"wp-media/phpunit": "^2.0"
```

## Custom Bootstrapping Your Repo

Sometimes you need a custom bootstrapping solution in your repo, such as loading a factory, handling licensing, etc. Here are the steps to get you rolling:

1. Add a `bootstrap.php` file in `Unit` or `Integration` directory.
2. In your `Tests/Integration/bootstrap.php` file, add the following code to it:

```php
tests_add_filter(
	'muplugins_loaded',
	function() {
		// Do your bootstrapping work here.
	}
);
```

## Custom Test Case

When you need to customize the test case, extend off of the base test cases in this package:

- For a custom integration, extend off of `WPMedia\PHPUnit\Integration\TestCase`.
- For a custom unit, extend off of `WPMedia\PHPUnit\Unit\TestCase`.
