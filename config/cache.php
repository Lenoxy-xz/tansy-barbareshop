<?php
return [
    'default' => env('CACHE_DRIVER', 'file'),
    'stores' => [
        'file' => ['driver' => 'file', 'path' => storage_path('framework/cache/data'), 'lock_path' => storage_path('framework/cache/data')],
        'database' => ['driver' => 'database', 'table' => 'cache', 'connection' => null, 'lock_table' => 'cache_locks', 'lock_connection' => null],
        'array' => ['driver' => 'array', 'serialize' => false],
        'null' => ['driver' => 'null'],
    ],
    'prefix' => env('CACHE_PREFIX', 'tansy_cache'),
];
