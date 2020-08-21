<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Responder\Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginAction extends Action
{
    private Responder $responder;

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->responder->render($response, 'users/login.twig');
    }
}
