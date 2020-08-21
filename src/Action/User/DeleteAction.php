<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserAuthService;
use App\Domain\User\Service\UserDeleteService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Odan\Session\FlashInterface as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class DeleteAction extends Action
{
    private UserDeleteService $userDeleteService;

    private UserAuthService $userAuthService;

    private RouteParser $router;

    private Flash $flash;

    public function __construct(
        UserDeleteService $userDeleteService,
        UserAuthService $userAuthService,
        RouteParser $router,
        Flash $flash
    ) {
        $this->userDeleteService = $userDeleteService;
        $this->userAuthService = $userAuthService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $currentUser = $this->userAuthService->getUser();
        if ($currentUser->id === (int) $id) {
            $this->flash->add('error', 'You cannot delete currently logged in user');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }

        if ($this->userDeleteService->delete((int) $id)) {
            $this->flash->add('success', 'User deleted successfully');
        } else {
            $this->flash->add('error', 'Unable to delete user');
        }

        return $response->withRedirect($this->router->urlFor('users.index'));
    }
}
