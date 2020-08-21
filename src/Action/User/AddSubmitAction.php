<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserCreateService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Views\Twig;
use Odan\Session\FlashInterface as Flash;

class AddSubmitAction extends Action
{
    private UserCreateService $userCreateService;

    private RouteParser $router;

    private Twig $view;

    private Flash $flash;

    public function __construct(
        UserCreateService $userCreateService,
        RouteParser $router,
        Twig $view,
        Flash $flash
    ) {
        $this->userCreateService = $userCreateService;
        $this->router = $router;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $data = (array) $request->getParsedBody();

        if ($this->userCreateService->save($data)) {
            $this->flash->add('success', 'User added successfully');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }
        $this->flash->add('error', 'Unable to add user');

        return $this->view->render($response, 'users/add.twig', $data);
    }
}
