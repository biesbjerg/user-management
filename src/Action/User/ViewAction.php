<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReaderService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;
use Odan\Session\FlashInterface as Flash;

class ViewAction extends Action
{
    private UserReaderService $userReaderService;

    private Twig $view;

    private Flash $flash;

    public function __construct(UserReaderService $userReaderService, Twig $view, Flash $flash)
    {
        $this->userReaderService = $userReaderService;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $user = $this->userReaderService->get($id);
        if (!$user) {
            $this->flash->add('error', 'User not found');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }

        return $this->view->render($response, 'users/view.twig', compact('user'));
    }
}
