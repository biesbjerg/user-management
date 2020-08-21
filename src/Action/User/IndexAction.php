<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReadService;
use App\Responder\Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class IndexAction extends Action
{
    private UserReadService $userReadService;

    private Responder $responder;

    public function __construct(Responder $responder, UserReadService $userReadService)
    {
        $this->responder = $responder;
        $this->userReadService = $userReadService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $users = $this->userReadService->getAll();

        return $this->responder->render($response, 'users/index.twig', compact('users'));
    }
}
