<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\AbstractAction as Action;
use App\Domain\User\Service\UserService;
use App\Responder\HtmlResponder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class IndexAction extends Action
{
    private UserService $service;

    private HtmlResponder $responder;

    public function __construct(HtmlResponder $responder, UserService $service)
    {
        $this->responder = $responder;
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $users = $this->service->fetchAllUsers();

        return $this->responder->render($response, 'users/index', compact('users'));
    }
}
