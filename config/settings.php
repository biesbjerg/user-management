<?php
declare(strict_types=1);

return [
    'debug' => true,
    'timezone' => 'Europe/Copenhagen',
    'root' => dirname(__DIR__),
    'db' => [
        'className' => \Cake\Database\Connection::class,
        'driver' => \Cake\Database\Driver\Mysql::class,
        'persistent' => false,
        'encoding' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'quoteIdentifiers' => true,
        'timezone' => 'utc',
        'flags' => [],
        'cacheMetadata' => true,
        'host' => 'database',
        'database' => 'ordbogen',
        'username' => 'ordbogen',
        'password' => 'setter-jounce-carnauba',
    ],
    'session' => [
        'use_strict_mode' => 1,
        'use_trans_id' => 0,
        'use_cookies' => 1,
        'cookie_httponly' => 1,
        'cookie_samesite' => 'SameSite'
    ],
    'twig' => [
        'paths' => [
            __DIR__ . '/../templates',
        ],
        'options' => [
            'cache_path' => __DIR__ . '/../tmp/twig',
            'strict_variables' => true,
            'autoescape' => 'html',
        ]
      ]
];
