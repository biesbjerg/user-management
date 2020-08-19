<?php
declare(strict_types=1);

namespace App\Middleware;

use Odan\Session\SessionInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteParser;

class AuthMiddleware implements MiddlewareInterface
{
    private ResponseFactory $responseFactory;

    private SessionInterface $session;

    private RouteParser $router;

    private array $config = [
        'flashKey' => 'auth',
        'loginUrl' => 'auth.login',
        'loginSuccessMessage' => 'Please log in to access the requested resource'
    ];

    public function __construct(ResponseFactory $responseFactory, SessionInterface $session, RouteParser $router)
    {
        $this->responseFactory = $responseFactory;
        $this->session = $session;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->session->get('Auth');
        if (!$user) {
            if ($this->config['loginSuccessMessage']) {
                $this->session->getFlash()->set($this->config['flashKey'], [$this->config['loginSuccessMessage']]);
            }

            $redirectUrl = $this->router->urlFor($this->config['loginUrl']);
            return $this->responseFactory
                ->createResponse()
                ->withHeader('Location', $redirectUrl)
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
