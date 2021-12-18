<?php

namespace App\Controllers\Auth;

use Exception;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Controllers\Validate\ValidateInController;

class SignInController extends ValidateInController
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
        return $this->view->render($response, 'pages/auth/signin.twig', [
            'redirect' => $request->getQueryParams()['redirect'] ?? null
        ]);
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response)
     {
        $data = $this->validate($request, [
            'email' => ['email', 'required'],
            'password' => ['required']
        ]);
        
        try {
            if(
                !$user = Sentinel::authenticate(
                    array_only($data, ['email', 'password']),
                    isset($data['persist'])
                )
            ) {
                throw new Exception('Введен неверный логин или пароль');
            }
        } catch (Exception $e) {
            
            $this->flash->addMessage('status', $e->getMessage());
            return $response->withHeader(
                'Location', $this->routeParser->urlFor('auth.signin')
            );
        }

        return $response->withHeader(
            'Location', $data['redirect'] ? $data['redirect'] : $this->routeParser->urlFor('home')
        );
    }
}