<?php

namespace App\Controllers\Media;

use Slim\Views\Twig;

class PhotogalleryController
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke($request, $response)
    {
        return $this->view->render($response, 'pages/media/photogallery.twig');
    }
}