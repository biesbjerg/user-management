<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Action\AbstractAction as Action;
use App\Service\User\UserService;
use App\Exception\ValidationException;
use App\Responder\HtmlResponder;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Odan\Session\FlashInterface as Flash;

class EditSubmitAction extends Action
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

    public function __invoke(Request $request, Response $response, string $id): Response
    {
        $formData = (array) $request->getParsedBody();

        try {
            $this->service->update($id, $formData);
            $this->flash->add('success', 'User updated successfully');

            return $this->responder->redirect($response, $this->router->urlFor('users.index'));
        } catch (ValidationException $e) {
            $this->flash->add('error', $e->getMessage());
            $this->responder->setTemplateVar('validator', $e->getValidator());
        } catch (Exception $e) {
            $this->flash->add('error', 'Unknown error occurred');
        }

        return $this->responder->render($response, 'users/edit', $formData);
    }
}
