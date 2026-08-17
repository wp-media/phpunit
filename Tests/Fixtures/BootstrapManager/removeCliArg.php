<?php

return [
	'removes the matching argument in place' => [
		'argv'     => [ 'x', 'unit', 'path=Tests/Foo', '--filter', 'bar' ],
		'key'      => 'path',
		// Unset leaves a gap; the remaining keys are not reindexed.
		'expected' => [ 0 => 'x', 1 => 'unit', 3 => '--filter', 4 => 'bar' ],
	],

	'leaves argv untouched when the arg is absent' => [
		'argv'     => [ 'x', 'unit', '--filter', 'bar' ],
		'key'      => 'path',
		'expected' => [ 'x', 'unit', '--filter', 'bar' ],
	],
];
