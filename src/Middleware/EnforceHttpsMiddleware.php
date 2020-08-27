<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class EnforceHttpsMiddleware implements MiddlewareInterface
{
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $uri = $request->getUri();
        if ($uri->getScheme() !== 'https') {
            $url = (string) $uri->withScheme('https')->withPort(443);

            return $this->responseFactory
                ->createResponse()
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
