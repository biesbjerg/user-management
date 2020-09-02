<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\AbstractAction as Action;
use App\Service\User\UserService;
use App\Responder\HtmlResponder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class IndexAction extends Action
{
    private UserService $userService;

    private HtmlResponder $responder;

    public function __construct(HtmlResponder $responder, UserService $userService)
    {
        $this->responder = $responder;
        $this->userService = $userService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $users = $this->userService->fetchAll();

        return $this->responder->render($response, 'users/index', compact('users'));
    }
}
