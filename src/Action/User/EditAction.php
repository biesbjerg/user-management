<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserService;
use App\Responder\Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends Action
{
    private Responder $responder;

    private UserService $service;

    public function __construct(Responder $responder, UserService $service)
    {
        $this->responder = $responder;
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, $id): Response
    {
        $data = $this->service->fetchUser((int) $id);

        return $this->responder->render($response, 'users/edit', $data);
    }
}
