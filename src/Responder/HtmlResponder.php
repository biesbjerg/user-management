<?php
declare(strict_types=1);

namespace App\Responder;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class HtmlResponder implements ResponderInterface
{
    private Twig $twig;

    private array $templateVars = [];

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function setTemplateVar(string $key, $val): void
    {
        $this->templateVars[$key] = $val;
    }

    public function render(Response $response, string $template, array $templateVars = []): Response
    {
        return $this->twig->render($response, $this->getTemplate($template), $templateVars + $this->templateVars);
    }

    public function redirect(Response $response, string $url, int $status = 302): Response
    {
        return $response->withHeader('Location', $url)->withStatus($status);
    }

    protected function getTemplate(string $template): string
    {
        if (substr($template, -5) !== '.twig') {
            return $template . '.twig';
        }

        return $template;
    }
}
