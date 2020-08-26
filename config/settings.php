<?php
declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required([
    'DATABASE_HOST',
    'DATABASE',
    'DATABASE_USER',
    'DATABASE_PASSWORD'
]);

return [
    'debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'enforce_https' => filter_var(env('ENFORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN),
    'timezone' => 'Europe/Copenhagen',
    'root' => dirname(__DIR__),
    'db' => [
        'className' => \Cake\Database\Connection::class,
        'driver' => \Cake\Database\Driver\Mysql::class,
        'persistent' => false,
        'encoding' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'quoteIdentifiers' => true,
        'timezone' => 'Europe/Copenhagen',
        'flags' => [],
        'cacheMetadata' => true,
        'host' => env('DATABASE_HOST', 'localhost'),
        'database' => env('DATABASE', 'users'),
        'username' => env('DATABASE_USER', ''),
        'password' => env('DATABASE_PASSWORD', ''),
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
            'cache' => __DIR__ . '/../tmp/twig',
            'strict_variables' => true,
            'autoescape' => 'html',
        ]
    ]
];
