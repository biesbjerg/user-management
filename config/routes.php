<?php
declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\UsersController;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app) {
    $app->redirect('/', '/users')->setName('home');

    $app->map(['GET', 'POST'], '/login', AuthController::class . ':login')->setName('auth.login');
    $app->get('/logout', AuthController::class . ':logout')->setName('auth.logout');

    $app->group('/users', function (RouteCollectorProxyInterface $group) {
        $group->get('', UsersController::class . ':index')->setName('users.index');
        $group->map(['GET', 'POST'], '/add', UsersController::class . ':add')->setName('users.add');
        $group->group('/{id:[0-9]+}', function (RouteCollectorProxyInterface $group) {
            $group->get('', UsersController::class . ':view')->setName('users.view');
            $group->map(['GET', 'PUT'], '/edit', UsersController::class . ':edit')->setName('users.edit');
            $group->delete('/delete', UsersController::class . ':delete')->setName('users.delete');
        });
    })->add(AuthMiddleware::class);
};
