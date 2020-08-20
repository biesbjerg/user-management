<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\User\Service\UserReaderService;
use Odan\Session\FlashInterface as Flash;
use Psr\Http\Message\ResponseInterface;

use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;

class UsersController extends Controller
{
    private UserReaderService $userReaderService;

    private Twig $view;

    private RouteParser $router;

    private Flash $flash;

    public function __construct(
        UserReaderService $userReaderService,
        RouteParser $router,
        Twig $view,
        Flash $flash
    ) {
        $this->userReaderService = $userReaderService;
        $this->router = $router;
        $this->view = $view;
        $this->flash = $flash;
    }

    public function index(Request $request, Response $response): ResponseInterface
    {
        $users = $this->userReaderService->getAll();

        return $this->view->render($response, 'Users/index.twig', compact('users'));
    }

    public function view(Request $request, Response $response, $id): ResponseInterface
    {
        $user = $this->userReaderService->get($id);
        if (!$user) {
            $this->flash->add('error', 'User not found');
            return $response->withRedirect($this->router->urlFor('users.index'));
        }

        return $this->view->render($response, 'Users/view.twig', compact('user'));
    }

    public function add(Request $request, Response $response): ResponseInterface
    {
        if ($request->isPost()) {
            $data = (array) $request->getParsedBody();
            // TODO: Validate & save
            $validates = false;
            if ($validates) {
                $this->flash->add('success', 'User created successfully');
                return $response->withRedirect($this->router->urlFor('users.index'));
            }
            $this->flash->add('error', 'Unable to create user');
        } else {
            $data = [];
        }

        return $this->view->render($response, 'Users/add.twig', $data);
    }

    public function edit(Request $request, Response $response, $id): ResponseInterface
    {
        if ($request->isPut()) {
            $data = (array) $request->getParsedBody();
            // TODO: Validate & save
            $validates = true;
            if ($validates) {
                $this->flash->add('success', 'User updated successfully');
                return $response->withRedirect($this->router->urlFor('users.index'));
            }
            $this->flash->add('error', 'Unable to update user');
        } else {
            $data = $this->userReaderService->get($id);
        }

        return $this->view->render($response, 'Users/edit.twig', $data);
    }

    public function delete(Request $request, Response $response, $id): ResponseInterface
    {
        // TODO: Delete
        $deleted = true;
        if ($deleted) {
            $this->flash->add('success', 'User deleted successfully');
        } else {
            $this->flash->add('error', 'Unable to delete user');
        }

        return $response->withRedirect($this->router->urlFor('users.index'));
    }
}
