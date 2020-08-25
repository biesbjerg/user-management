<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\AbstractAction as Action;
use App\Service\User\AuthService;
use App\Responder\HtmlResponder;
use DateTime;
use Odan\Session\FlashInterface as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginSubmitAction extends Action
{
    private HtmlResponder $responder;

    private AuthService $authService;

    private RouteParser $router;

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
        $formData = (array) $request->getParsedBody();

        $username = $formData['username'] ?? '';
        $password = $formData['password'] ?? '';

        $user = $this->authService->authenticate($username, $password);
        if ($user) {
            $this->authService->setUser($user);
            $this->authService->updateLastLogin($user);

            $lastLogin = 'never';
            if ($user->last_login) {
                $lastLogin = (new DateTime($user->last_login))->format('l, j. F Y H:i');
            }

            $this->flash->add('success', sprintf('Welcome, %s! Last login: %s', $user->name, $lastLogin));

            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        } else {
            $this->flash->add('error', 'Invalid username or password');
        }

        return $this->responder->render($response, 'users/login', compact('username'));
    }
}
