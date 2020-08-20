<?php
declare(strict_types=1);

namespace App\Action\Auth;

use App\Action\Action;
use App\Domain\User\Service\UserSessionService;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class LogoutAction extends Action
{
    private RouteParser $router;

    private UserSessionService $userSessionService;

    private Flash $flash;

    public function __construct(UserSessionService $userSessionService, RouteParser $router, Flash $flash)
    {
        $this->userSessionService = $userSessionService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $this->userSessionService->clear();
        $this->flash->add('info', 'You have been logged out');

        return $response->withRedirect($this->router->urlFor('auth.login'));
    }
}
