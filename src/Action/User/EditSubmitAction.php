<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Views\Twig;
use Odan\Session\FlashInterface as Flash;

class EditSubmitAction extends Action
{
    private RouteParser $router;

    private Twig $view;

    private Flash $flash;

    public function __construct(RouteParser $router, Twig $view, Flash $flash)
    {
        $this->router = $router;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $data = (array) $request->getParsedBody();

        // TODO: Validate & save
        $validates = true;
        if ($validates) {
            $this->flash->add('success', 'User updated successfully');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }
        $this->flash->add('error', 'Unable to update user');

        return $this->view->render($response, 'Users/edit.twig', $data);
    }
}
