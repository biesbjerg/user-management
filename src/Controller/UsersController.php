<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\User\Service\UserReaderService;
use Psr\Http\Message\ResponseInterface;

use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;

class UsersController extends Controller
{
    private Twig $view;

    private UserReaderService $userService;

    public function __construct(RouteParser $router, Twig $view, UserReaderService $userService)
    {
        $this->router = $router;
        $this->view = $view;
        $this->userService = $userService;
    }

    public function index(Request $request, Response $response): ResponseInterface
    {
        $users = $this->userService->getAll();

        return $this->view->render($response, 'Users/index.twig', compact('users'));
    }

    public function view(Request $request, Response $response, $id): ResponseInterface
    {
        $user = $this->userService->get($id);
        if (!$user) {
            // TODO: Handle better
            $response->getBody()->write(sprintf('User not found: %d', $id));
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'Users/view.twig', compact('user'));
    }

    public function add(Request $request, Response $response): ResponseInterface
    {
        if ($request->isPost()) {
            // TODO: Validate & save
            $validates = true;
            if ($validates) {
                // TODO: Set flash
                return $response->withRedirect($this->router->urlFor('users.index'));
            }
        }

        return $this->view->render($response, 'Users/add.twig', [
            // Entity or POST-data
        ]);
    }

    public function edit(Request $request, Response $response, $id): ResponseInterface
    {
        if ($request->isPost()) {
            // TODO: Validate & save
            $validates = true;
            if ($validates) {
                // TODO: Set flash
                return $response->withRedirect($this->router->urlFor('users.index'));
            }
        } else {
            // TODO: Get user with $id
        }

        return $this->view->render($response, 'Users/edit.twig', [
            // Entity or POST-data
        ]);
    }

    public function delete(Request $request, Response $response, $id): ResponseInterface
    {
        // TODO: Delete
        $deleted = true;
        if ($deleted) {
            // TODO: Set flash
        }

        return $response->withRedirect($this->router->urlFor('users.index'));
    }
}
