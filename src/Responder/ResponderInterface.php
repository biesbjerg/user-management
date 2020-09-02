<?php
declare(strict_types=1);

namespace App\Responder;

use Psr\Http\Message\ResponseInterface as Response;

interface ResponderInterface
{
    /**
     * Undocumented function
     *
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function setTemplateVar(string $key, $val): void;

    public function render(Response $response, string $template, array $templateVars = []): Response;
}
