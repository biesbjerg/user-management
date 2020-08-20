<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReaderService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;

class EditAction extends Action
{
    private UserReaderService $userReaderService;

    private Twig $view;

    public function __construct(UserReaderService $userReaderService, Twig $view)
    {
        $this->userReaderService = $userReaderService;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $data = $this->userReaderService->get($id);

        return $this->view->render($response, 'Users/edit.twig', $data);
    }
}
