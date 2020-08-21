<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserCreateService;
use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Odan\Session\FlashInterface as Flash;

class AddSubmitAction extends Action
{
    private Responder $responder;

    private UserCreateService $userCreateService;

    private RouteParser $router;

    private Flash $flash;

    public function __construct(
        Responder $responder,
        UserCreateService $userCreateService,
        RouteParser $router,
        Flash $flash
    ) {
        $this->responder = $responder;
        $this->userCreateService = $userCreateService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $data = (array) $request->getParsedBody();

        if ($this->userCreateService->save($data)) {
            $this->flash->add('success', 'User added successfully');
            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        }
        $this->flash->add('error', 'Unable to add user');

        return $this->responder->render($response, 'users/add.twig', $data);
    }
}
