<?php

namespace App\Controllers;

use Slim\Views\Twig;

class AnswerController
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke($request, $response)
    {
        return $this->view->render($response, 'pages/answer/index.twig');
    }
}