<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserAuthService;
use App\Responder\Responder;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;

class LoginSubmitAction extends Action
{
    private Responder $responder;

    private UserAuthService $userAuthService;

    private RouteParser $router;

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

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $username = $request->getParsedBodyParam('username', '');
        $password = $request->getParsedBodyParam('password', '');

        $user = $this->userAuthService->authenticate($username, $password);
        if ($user) {
            $this->userAuthService->updateLastLogin($user->id);
            $this->userAuthService->setUser($user);

            $this->flash->add('success', sprintf(
                'Welcome, %s! Last login: %s',
                $user->name,
                $user->lastLogin ? $user->lastLogin->format('l, j. F Y H:i') : 'never'
            ));
            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        } else {
            $this->flash->add('error', 'Invalid username or password');
        }

        return $this->responder->render($response, 'users/login.twig', compact('username'));
    }
}
