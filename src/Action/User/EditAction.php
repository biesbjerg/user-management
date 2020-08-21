<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReadService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Views\Twig;

class EditAction extends Action
{
    private UserReadService $userReadService;

    private Twig $view;

    public function __construct(UserReadService $userReadService, Twig $view)
    {
        $this->userReadService = $userReadService;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $data = $this->userReadService->getById((int) $id);

        return $this->view->render($response, 'users/edit.twig', $data);
    }
}
