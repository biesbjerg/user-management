<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserService;
use App\Responder\Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Odan\Session\FlashInterface as Flash;

class EditSubmitAction extends Action
{
    private Responder $responder;

    private UserService $service;

    private RouteParser $router;

    private Flash $flash;

    public function __construct(
        Responder $responder,
        UserService $service,
        RouteParser $router,
        Flash $flash
    ) {
        $this->responder = $responder;
        $this->service = $service;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $id): Response
    {
        $formData = [
            'name' => $request->getParsedBody()['name'] ?? '',
            'username' => $request->getParsedBody()['username'] ?? '',
            'password' => $request->getParsedBody()['password'] ?? '',
            'is_enabled' => $request->getParsedBody()['is_enabled'] ?? ''
        ];

        if ($this->service->update((int) $id, $formData)) {
            $this->flash->add('success', 'User updated successfully');
            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        }
        $this->flash->add('error', 'Unable to update user');

        return $this->responder->render($response, 'users/edit', $formData);
    }
}
