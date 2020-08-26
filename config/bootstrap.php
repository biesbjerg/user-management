<?php
declare(strict_types=1);

use Slim\App;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/settings.php';

error_reporting(0);
ini_set('display_errors', '0');
if ($settings['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $settings['twig']['options']['cache_path'] = false;
}

date_default_timezone_set($settings['timezone']);

$containerBuilder = new ContainerBuilder();

if (!$settings['debug']) {
    $containerBuilder->enableCompilation(__DIR__ . '/../tmp/cache');
    $containerBuilder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
}

$containerBuilder->addDefinitions(__DIR__ . '/container.php');
$container = $containerBuilder->build();
$app = $container->get(App::class);

(require __DIR__ . '/routes.php')($app);
(require __DIR__ . '/middleware.php')($app);

return $app;
