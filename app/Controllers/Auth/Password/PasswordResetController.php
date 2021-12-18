<?php

namespace App\Controllers\Auth\Password;

use Exception;
use App\Models\User;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Controllers\Validate\ValidateResetController;

class PasswordResetController extends ValidateResetController
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
        $params = array_only($request->getQueryParams(), ['email', 'code']);

        if (
            !$this->activationCodeExists(
                User::whereEmail($email = $params['email']  ?? null)->first(),
                $code = $params['code'] ?? null
            )
        ) { 
            return $response->withHeader('Location', $this->routeParser->urlFor('home'));
        }

        return $this->view->render($response, 'pages/auth/password/reset.twig', compact('email', 'code'));
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response)
     {
        $data = $this->validate($request, [
            'password' => ['required']
        ]);

        $params = array_only($data, ['email', 'code', 'password']);

        if (
            !$this->activationCodeExists(
                $user = User::whereEmail($params['email']  ?? null)->first(),
                $code = $params['code'] ?? null
            )
        ) { 
            return $response->withHeader('Location', $this->routeParser->urlFor('home'));
        }

        Sentinel::getReminderRepository()->complete($user, $code, $params['password']);

        $this->flash->addMessage('status', 'Ваш старый пароль был успешно сброшен. Теперь Вы можете войти в свой аккаунт, используя новый пароль');

        return $response->withHeader('Location', $this->routeParser->urlFor('auth.signin'));
    }

    protected function activationCodeExists(?User $user, $code)
    {
        if(!$user) {
            return false;
        }

        if(!Sentinel::getReminderRepository()->exists($user , $code)) {
            return false;
        }

        return true;
    }
}