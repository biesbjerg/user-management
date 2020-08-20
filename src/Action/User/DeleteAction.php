<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserDeleterService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Odan\Session\FlashInterface as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class DeleteAction extends Action
{
    private UserDeleterService $userDeleterService;

    private RouteParser $router;

    private Flash $flash;

    public function __construct(UserDeleterService $userDeleterService, RouteParser $router, Flash $flash)
    {
        $this->userDeleterService = $userDeleterService;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        if ($this->userDeleterService->delete($id)) {
            $this->flash->add('success', 'User deleted successfully');
        } else {
            $this->flash->add('error', 'Unable to delete user');
        }

        return $response->withRedirect($this->router->urlFor('users.index'));
    }
}
