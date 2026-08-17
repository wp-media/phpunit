<?php

return [
	'flag matches the requested group name' => [
		'argv'       => [ 'x', 'integration', '--group', 'AdminOnly' ],
		'group_name' => 'AdminOnly',
		'expected'   => true,
	],

	'flag name differs from the requested group' => [
		'argv'       => [ 'x', 'integration', '--group', 'AdminOnly' ],
		'group_name' => 'Multisite',
		'expected'   => false,
	],

	'no group flag present' => [
		'argv'       => [ 'x', 'integration' ],
		'group_name' => 'AdminOnly',
		'expected'   => false,
	],

	'group flag has no name after it' => [
		'argv'       => [ 'x', 'integration', '--group' ],
		'group_name' => 'AdminOnly',
		'expected'   => false,
	],
];
