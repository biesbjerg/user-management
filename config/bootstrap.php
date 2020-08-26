<?php
declare(strict_types=1);

use Slim\App;
use DI\ContainerBuilder;

require __DIR__ . '/paths.php';

require ROOT . '/vendor/autoload.php';

$settings = require CONFIG . '/settings.php';

error_reporting(0);
ini_set('display_errors', '0');
if ($settings['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

date_default_timezone_set($settings['timezone']);

$containerBuilder = new ContainerBuilder();

if (!$settings['debug']) {
    $containerBuilder->enableCompilation(CACHE);
    $containerBuilder->writeProxiesToFile(true, CACHE . '/proxies');
}

$containerBuilder->addDefinitions(CONFIG . '/container.php');
$container = $containerBuilder->build();
$app = $container->get(App::class);

(require CONFIG . '/routes.php')($app, $settings);
(require CONFIG . '/middleware.php')($app, $settings);

return $app;
