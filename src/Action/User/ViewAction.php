<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReadService;
use App\Responder\Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Odan\Session\FlashInterface as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class ViewAction extends Action
{
    private Responder $responder;

    private UserReadService $userReadService;

    private Flash $flash;

    private RouteParser $router;

    public function __construct(
        Responder $responder,
        UserReadService $userReadService,
        Flash $flash,
        RouteParser $router
    ) {
        $this->responder = $responder;
        $this->userReadService = $userReadService;
        $this->flash = $flash;
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $id): Response
    {
        $user = $this->userReadService->getById((int) $id);
        if (!$user) {
            $this->flash->add('error', 'User not found');
            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        }

        return $this->responder->render($response, 'users/view', compact('user'));
    }
}
