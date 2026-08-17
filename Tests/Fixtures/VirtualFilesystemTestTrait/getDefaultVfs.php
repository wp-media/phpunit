<?php

declare(strict_types=1);

return [
	'wp-like default structure' => [
		[
			'wp-admin'      => [],
			'wp-content'    => [
				'mu-plugins' => [],
				'plugins'    => [
					'wp-rocket' => [],
				],
				'themes'     => [
					'twentytwenty' => [],
				],
				'uploads'    => [],
			],
			'wp-includes'   => [],
			'wp-config.php' => '',
		],
	],
];
