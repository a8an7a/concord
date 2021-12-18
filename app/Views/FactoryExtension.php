<?php

namespace App\Views;

use Slim\Views\Twig;

class FactoryExtension
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function make($view, $data = [])
    {
        return $this->view->fetch($view, $data);
    }

    public function render()
    {
        return $this->view;
    }
}
