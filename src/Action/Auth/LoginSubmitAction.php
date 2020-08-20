<?php
declare(strict_types=1);

namespace App\Action\Auth;

use App\Action\Action;
use App\Domain\User\Service\UserAuthService;
use App\Domain\User\Service\UserSessionService;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;

class LoginSubmitAction extends Action
{
    private UserAuthService $userAuthService;

    private UserSessionService $userSessionService;

    private RouteParser $router;

    private Twig $view;

    private Flash $flash;

    public function __construct(
        UserAuthService $userAuthService,
        UserSessionService $userSessionService,
        RouteParser $router,
        Twig $view,
        Flash $flash
    ) {
        $this->userAuthService = $userAuthService;
        $this->userSessionService = $userSessionService;
        $this->router = $router;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $username = $request->getParsedBodyParam('username', '');
        $password = $request->getParsedBodyParam('password', '');

        $user = $this->userAuthService->authenticate($username, $password);
        if ($user) {
            $this->userAuthService->updateLastLogin($user->id);
            $this->userSessionService->set($user);

            $this->flash->add('success', sprintf(
                'Welcome, %s! Last login: %s',
                $user->name,
                $user->lastLogin ? $user->lastLogin->format('l, j. F Y H:i') : 'never'
            ));
            return $response->withRedirect($this->router->urlFor('users.index'));
        } else {
            $this->flash->add('error', 'Invalid username or password');
        }

        return $this->view->render($response, 'Auth/login.twig', compact('username'));
    }
}
