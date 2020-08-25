<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\AbstractAction as Action;
use App\Domain\User\Service\UserService;
use App\Exception\ValidationException;
use App\Responder\HtmlResponder;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Odan\Session\FlashInterface as Flash;

class AddSubmitAction extends Action
{
    private HtmlResponder $responder;

    private UserService $service;

    private RouteParser $router;

    private Flash $flash;

    public function __construct(
        HtmlResponder $responder,
        UserService $service,
        RouteParser $router,
        Flash $flash
    ) {
        $this->responder = $responder;
        $this->service = $service;
        $this->router = $router;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $formData = (array) $request->getParsedBody();

        try {
            $this->service->create($formData);
            $this->flash->add('success', 'User added successfully');

            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        } catch (ValidationException $e) {
            $this->flash->add('error', $e->getMessage());
            $this->responder->setTemplateVar('validator', $e->getValidator());
        } catch (Exception $e) {
            $this->flash->add('error', 'Unknown error occurred');
        }

        return $this->responder->render($response, 'users/add', $formData);
    }
}
