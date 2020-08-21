<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\Action;
use App\Domain\User\Service\UserReadService;
use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;

class EditAction extends Action
{
    private Responder $responder;

    private UserReadService $userReadService;

    public function __construct(Responder $responder, UserReadService $userReadService)
    {
        $this->responder = $responder;
        $this->userReadService = $userReadService;
    }

    public function __invoke(Request $request, Response $response, $id): ResponseInterface
    {
        $data = $this->userReadService->getById((int) $id);

        return $this->responder->render($response, 'users/edit.twig', $data);
    }
}
