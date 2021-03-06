<?php
declare(strict_types=1);

use App\Service\User\AuthService;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Container\ContainerInterface;
use Cake\Database\Connection;
use Odan\Session\FlashInterface;
use Odan\Session\SessionInterface;
use Odan\Session\PhpSession;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\Interfaces\RouteParserInterface;
use App\Middleware\EnforceHttpsMiddleware;

return [
    'settings' => function (): array {
        return require CONFIG . '/settings.php';
    },

    App::class => function (ContainerInterface $container): App {
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $routeCollector = $app->getRouteCollector();
        $routeCollector->setCacheFile(CACHE . '/route.file');
        $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
        return $app;
    },

    RouteParserInterface::class => function (ContainerInterface $container): RouteParserInterface {
        $app = $container->get(App::class);
        return $app->getRouteCollector()->getRouteParser();
    },

    Connection::class => function (ContainerInterface $container): Connection {
        $settings = $container->get('settings');
        return new Connection($settings['db']);
    },

    SessionInterface::class => function (ContainerInterface $container): SessionInterface {
        $settings = $container->get('settings');
        $session = new PhpSession();
        $session->setOptions($settings['session']);
        return $session;
    },

    SessionMiddleware::class => function (ContainerInterface $container): SessionMiddleware {
        return new SessionMiddleware($container->get(SessionInterface::class));
    },

    FlashInterface::class => function (ContainerInterface $container): FlashInterface {
        $session = $container->get(SessionInterface::class);
        return $session->getFlash();
    },

    EnforceHttpsMiddleware::class => function (ContainerInterface $container): EnforceHttpsMiddleware {
        return new EnforceHttpsMiddleware(
            $container->get(App::class)->getResponseFactory()
        );
    },

    ErrorMiddleware::class => function (ContainerInterface $container): ErrorMiddleware {
        $app = $container->get(App::class);
        $settings = $container->get('settings');
        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            $displayErrorDetails = $settings['debug'],
            $logErrors = true,
            $logErrorDetails = true
        );
    },

    Twig::class => function (ContainerInterface $container): Twig {
        $settings = $container->get('settings');
        $options = $settings['twig']['options'] + [
            'debug' => $settings['debug']
        ];
        $twig = Twig::create($settings['twig']['paths'], $options);
        $twig->getEnvironment()->addGlobal('flash', $container->get(SessionInterface::class)->getFlash());
        $twig->getEnvironment()->addGlobal('auth', $container->get(AuthService::class)->getUser());
        return $twig;
    },

    TwigMiddleware::class => function (ContainerInterface $container): TwigMiddleware {
        return TwigMiddleware::createFromContainer(
            $container->get(App::class),
            Twig::class
        );
    },
];
