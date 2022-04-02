<?php

$settings = [];

/**
 * Database connection.
 */
$settings['db'] = [
    'default' => 'default',
    'databases' => [
        'default' => ['connection' => 'default'],
    ],
    'connections' => [
        'default' => [
            'driver' => \Cycle\Database\Driver\SQLite\SQLiteDriver::class,
            'connection' => 'sqlite:' . __DIR__ . '/../database/**.db',
            'queryCache' => true,
        ],
    ],
];

/**
 * JWT Settings.
 */
$settings['jwt'] = [
    // issuer name
    'issuer' => 'www.example.com',

    // life in seconds
    'lifetime' => 4 * 60 * 60,

    // data keys
    'private_key' => 'path to private.pem',
    'public_key' => 'path to public.pem',
    'keyword' => 'super-secret-key-that-no-one-knows-about',
];

return $settings;
