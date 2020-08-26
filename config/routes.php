<?php
declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app, array $settings) {
    $app->redirect('/', '/users')->setName('home');

    $app->get('/login', \App\Action\User\LoginAction::class)->setName('users.login');
    $app->post('/login', \App\Action\User\LoginSubmitAction::class);
    $app->get('/logout', \App\Action\User\LogoutAction::class)->setName('users.logout');

    $app->group('/users', function (RouteCollectorProxyInterface $group) {
        $group->get('', \App\Action\User\IndexAction::class)->setName('users.index');

        $group->get('/add', \App\Action\User\AddAction::class)->setName('users.add');
        $group->post('/add', \App\Action\User\AddSubmitAction::class);

        $group->group('/{id:[0-9]+}', function (RouteCollectorProxyInterface $group) {
            $group->get('', \App\Action\User\ViewAction::class)->setName('users.view');

            $group->get('/edit', \App\Action\User\EditAction::class)->setName('users.edit');
            $group->put('/edit', \App\Action\User\EditSubmitAction::class);

            $group->delete('/delete', \App\Action\User\DeleteAction::class)->setName('users.delete');
        });
    })->add(AuthMiddleware::class);
};
