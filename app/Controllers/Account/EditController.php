<?php

namespace App\Controllers\Account;

use App\Models\User;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Slim\Psr7\UploadedFile;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use App\Controllers\Upload\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Controllers\Validate\ValidateEditController;

class EditController extends ValidateEditController
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
        return $this->view->render($response, 'pages/account/edit.twig');
    }

    public function action(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->validate($request, [
            'upload' => ['photoFormat'],
            'email'  => ['required', 'email', 'emailIsUnique'],
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'birthday'   => ['required', 'date']
        ]);
        
        $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
        
        if ($request->getUploadedFiles()['upload']->getClientFilename()) {
            $upload = new UploadController($request->getUploadedFiles()['upload'], 'profile');
            $data['upload'] = $upload->upload();
            unlink(__DIR__ . '/../../../public_html/upload/image/profile/' . Sentinel::check()->getUpload());
        } else {
            $data['upload'] = Sentinel::check()->getUpload(); 
        }

        Sentinel::check()->update(
            array_only($data, [
                'upload', 'email', 'first_name', 'last_name', 'birthday'
            ])
        );

        $this->flash->addMessage('status', 'Профиль успешно изменен!');

        return $response->withHeader('Location', $this->routeParser->urlFor('account.edit'));
    }
}