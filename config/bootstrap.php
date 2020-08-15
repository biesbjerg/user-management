<?php
declare(strict_types=1);

use Slim\App;
use DI\ContainerBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/settings.php';
error_reporting(E_ALL); // TODO?
if (function_exists('ini_set')) {
    ini_set('display_errors', $settings['debug'] ? '1' : '0');
}

date_default_timezone_set($settings['default_timezone']);

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/container.php');
$container = $containerBuilder->build();
$app = $container->get(App::class);

(require __DIR__ . '/routes.php')($app);
(require __DIR__ . '/middleware.php')($app);

return $app;
