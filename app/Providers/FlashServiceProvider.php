<?php

namespace App\Providers;

use Slim\Flash\Messages;
use League\Container\ServiceProvider\AbstractServiceProvider;

class FlashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'flash'
    ];

    public function register()
    {
        $this->getContainer()->share('flash', function () {
            return new Messages();
        });
    }
}