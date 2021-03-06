<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Service\User\AuthService;
use Odan\Session\FlashInterface as Flash;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Interfaces\RouteParserInterface as RouteParser;

class AuthMiddleware implements MiddlewareInterface
{
    private ResponseFactory $responseFactory;

    private AuthService $authService;

    private Flash $flash;

    private RouteParser $router;

    public function __construct(
        ResponseFactory $responseFactory,
        AuthService $authService,
        Flash $flash,
        RouteParser $router
    ) {
        $this->responseFactory = $responseFactory;
        $this->authService = $authService;
        $this->flash = $flash;
        $this->router = $router;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->authService->isAuthenticated()) {
            $this->flash->add('error', 'Please log in to access the requested resource');

            return $this->responseFactory
                ->createResponse()
                ->withHeader('Location', $this->router->urlFor('users.login'))
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
