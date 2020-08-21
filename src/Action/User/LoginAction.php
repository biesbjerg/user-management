<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;

class LoginAction extends Action
{
    private Responder $responder;

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        return $this->responder->render($response, 'users/login.twig');
    }
}
