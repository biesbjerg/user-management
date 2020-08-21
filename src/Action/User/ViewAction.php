<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReadService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;
use Odan\Session\FlashInterface as Flash;

class ViewAction extends Action
{
    private UserReadService $userReadService;

    private Twig $view;

    private Flash $flash;

    public function __construct(UserReadService $userReadService, Twig $view, Flash $flash)
    {
        $this->userReadService = $userReadService;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $user = $this->userReadService->getById((int) $id);
        if (!$user) {
            $this->flash->add('error', 'User not found');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }

        return $this->view->render($response, 'users/view.twig', compact('user'));
    }
}
