<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserUpdateService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Views\Twig;
use Odan\Session\FlashInterface as Flash;

class EditSubmitAction extends Action
{
    private UserUpdateService $userUpdateService;

    private RouteParser $router;

    private Twig $view;

    private Flash $flash;

    public function __construct(
        UserUpdateService $userUpdateService,
        RouteParser $router,
        Twig $view,
        Flash $flash
    ) {
        $this->userUpdateService = $userUpdateService;
        $this->router = $router;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $data = (array) $request->getParsedBody();

        // Don't update password if left blank
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($this->userUpdateService->save((int) $id, $data)) {
            $this->flash->add('success', 'User updated successfully');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }
        $this->flash->add('error', 'Unable to update user');

        return $this->view->render($response, 'users/edit.twig', $data);
    }
}
