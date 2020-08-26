<?php
declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/config');
$dotenv->load();
$dotenv->required([
    'DATABASE_HOST',
    'DATABASE',
    'DATABASE_USER',
    'DATABASE_PASSWORD'
]);

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'default',
        'default' => [
            'adapter' => 'mysql',
            'host' => env('DATABASE_HOST', 'localhost'),
            'name' => env('DATABASE', 'users'),
            'user' => env('DATABASE_USER', 'root'),
            'pass' => env('DATABASE_PASSWORD', ''),
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
