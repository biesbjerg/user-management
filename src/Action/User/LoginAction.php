<?php
declare(strict_types=1);

namespace App\Action\USer;

use App\Action\Action;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;

class LoginAction extends Action
{
    private Twig $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        return $this->view->render($response, 'users/login.twig');
    }
}
