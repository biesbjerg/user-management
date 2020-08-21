<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserAuthService;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class LogoutAction extends Action
{
    private RouteParser $router;

    private UserAuthService $userAuthService;

    private Flash $flash;

    public function __construct(UserAuthService $userAuthService, RouteParser $router, Flash $flash)
    {
        $this->userAuthService = $userAuthService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $this->userAuthService->logout();
        $this->flash->add('info', 'You have been logged out');

        return $response->withRedirect($this->router->urlFor('users.login'));
    }
}
