<?php

namespace App\Controllers\Account;

use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Controllers\Validate\ValidateChangePasswordController;

class AccountPasswordController extends ValidateChangePasswordController
{
    protected $view;
    protected $flash;
    protected $routeParser;

    public function __construct(Twig $view, Messages $flash, RouteParserInterface $routeParser)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'pages/account/password.twig');
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->validate($request, [
            'current_password' => ['required', 'currentPassword'],
            'password' => ['required']
        ]);

        Sentinel::getUserRepository()->update(
            Sentinel::check(),
            array_only($data, ['password'])
        );

        $this->flash->addMessage('status', 'Пароль успешно изменен!');
        
        return $response->withHeader('Location', $this->routeParser->urlFor('account.password'));
    }
}