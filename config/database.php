<?php

return [
    'fetch' => PDO::FETCH_ASSOC,
    'default' => env('DB_CONNECTION', 'default'),
    'log'     => env('DB_LOG', false),
    'connections' => [
        'default' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => 'utf8mb4_general_ci',
            'timezone' => env('DB_TIMEZONE', '+08:00'),
            'strict' => env('DB_STRICT_MODE', false)
        ],
        'mirror_db' => [
            'driver' => 'mysql',
            'host' => env('DB_MIRROR_HOST'),
            'port' => env('DB_MIRROR_PORT'),
            'database' => env('DB_MIRROR_DATABASE'),
            'username' => env('DB_MIRROR_USERNAME'),
            'password' => env('DB_MIRROR_PASSWORD'),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => 'utf8mb4_general_ci',
            'timezone' => env('DB_TIMEZONE', '+08:00'),
        ],
    ],
    'migrations' => 'migrations',
    'redis' => [
        'cluster' => env('REDIS_CLUSTER', false),
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'password' => env('REDIS_PASSWORD', null),
        ],
        'queue' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('QUEUE_REDIS_DB', 15),
            'password' => env('REDIS_PASSWORD', null),
        ],
    ],
];
