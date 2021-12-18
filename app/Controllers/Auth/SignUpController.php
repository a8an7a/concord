<?php

namespace App\Controllers\Auth;

use Exception;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use App\Controllers\Upload\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Controllers\Validate\ValidateUpController;

class SignUpController extends ValidateUpController
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
        return $this->view->render($response, 'pages/auth/signup.twig');
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response) 
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email', 'emailIsUnique'],
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'birthday'   => ['required', 'date'],
            'password'   => ['required'],
            'upload' => ['photoFormat']
        ]);

        $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));

        try {
            if ($request->getUploadedFiles()['upload']) {
                $upload = new UploadController($request->getUploadedFiles()['upload'], 'profile');
                $data['upload'] = $upload->upload();
            } 

            $user = Sentinel::register(
                array_only($data, ['email', 'first_name', 'last_name', 'birthday', 'password', 'upload']),
                true
            );

            Sentinel::authenticate(array_only($data, ['email', 'password']));
        } catch (Exception $e) {
            
            $this->flash->addMessage('status', 'Что-то пошло не так');
            return $response->withHeader('Location', $this->routeParser->urlFor('auth.signup'));
        }
        
        return $response->withHeader('Location', $this->routeParser->urlFor('home'));
    }
}






