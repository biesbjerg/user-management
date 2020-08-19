<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Domain\User\Service\UserAuthService;
use Odan\Session\FlashInterface as Flash;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteParser;

class AuthMiddleware implements MiddlewareInterface
{
    private ResponseFactory $responseFactory;

    private UserAuthService $userAuthService;

    private Flash $flash;

    private RouteParser $router;

    public function __construct(
        ResponseFactory $responseFactory,
        UserAuthService $userAuthService,
        Flash $flash,
        RouteParser $router
    ) {
        $this->responseFactory = $responseFactory;
        $this->userAuthService = $userAuthService;
        $this->flash = $flash;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->userAuthService->isAuthenticated()) {
            return $handler->handle($request);
        }

        $this->flash->set('auth', ['Please log in to access the requested resource']);

        return $this->responseFactory
            ->createResponse()
            ->withHeader('Location', $this->router->urlFor('auth.login'))
            ->withStatus(302);
    }
}
