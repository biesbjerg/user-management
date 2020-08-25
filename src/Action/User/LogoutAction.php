<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\AuthService;
use App\Responder\HtmlResponder;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class LogoutAction extends Action
{
    private HtmlResponder $responder;

    private RouteParser $router;

    private AuthService $authService;

    private Flash $flash;

    public function __construct(
        HtmlResponder $responder,
        AuthService $authService,
        RouteParser $router,
        Flash $flash
    ) {
        $this->responder = $responder;
        $this->authService = $authService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->authService->clearUser();
        $this->flash->add('info', 'You have been logged out');

        return $this->responder->redirect($response, $this->router->urlFor('users.login'));
    }
}
