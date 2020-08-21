<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserAuthService;
use App\Responder\Responder;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class LogoutAction extends Action
{
    private Responder $responder;

    private RouteParser $router;

    private UserAuthService $userAuthService;

    private Flash $flash;

    public function __construct(
        Responder $responder,
        UserAuthService $userAuthService,
        RouteParser $router,
        Flash $flash
    ) {
        $this->responder = $responder;
        $this->userAuthService = $userAuthService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->userAuthService->logout();
        $this->flash->add('info', 'You have been logged out');

        return $this->responder->redirect($response, $this->router->urlFor('users.login'));
    }
}
