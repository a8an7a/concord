<?php

namespace App\Controllers\Auth\Password;

use Exception;
use App\Models\User;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Controllers\Validate\ValidateRecoverController;

class PasswordRecoverController extends ValidateRecoverController
{
    protected $view;
    protected $flash;
    protected $routeParser;
    protected $mail;

    public function __construct(Twig $view, Messages $flash, RouteParserInterface $routeParser, PHPMailer $mail) 
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
        $this->mail = $mail;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response) 
    {
        return $this->view->render($response, 'pages/auth/password/recover.twig');
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response)
     {
        $data = $this->validate($request, [
            'email' => ['required', 'email']
        ]);
            
        $params = array_only($data, ['email']);

        if($user = User::whereEmail($params['email'])->first()) {
            $reminder = Sentinel::getReminderRepository()->create($user);
        }

        $this->mail->addAddress($user->email);

        $this->mail->Subject = 'Восстановление пароля МОО «Содружество военных автомобилистов»';
        $this->mail->Body = $this->view->fetch('email/auth/password/recover.twig', [
            'user' => $user, 
            'code' => $reminder->code
        ]);
        
        $this->mail->CharSet = 'UTF-8';
        $this->mail->send();

        $this->flash->addMessage(
            'status',
            'На ваш адрес электронной почты было выслано письмо с инструкцией по дальнейшему восстановлению пароля'
        );

        return $response->withHeader('Location', $this->routeParser->urlFor('auth.password.recover'));
    }
}