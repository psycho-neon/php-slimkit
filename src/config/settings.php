<?php

$settings = [];

$settings['db'] = [
    'default' => 'default',
    'databases' => [
        'default' => ['connection' => 'default']
    ],
    'connections' => [
        'default' => [
            'driver' => \Cycle\Database\Driver\SQLite\SQLiteDriver::class,
            'connection' => 'sqlite:<path to database',
        ]
    ]
];

// other static settings can be declared here
// Example:
// $settings['user'] = [...]

return $settings;
