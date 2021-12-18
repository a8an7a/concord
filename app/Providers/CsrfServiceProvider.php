<?php

namespace App\Providers;

use Slim\Csrf\Guard;
use Psr\Http\Message\ResponseFactoryInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CsrfServiceProvider extends AbstractServiceProvider
{
    protected $responseFacroty;
    
    protected $provides = [
        'csrf'
    ];

    public function __construct(ResponseFactoryInterface $responseFacroty)
    {
        $this->responseFacroty = $responseFacroty;
    }

    public function register()
    {
        $this->getContainer()->share('csrf', function () use($responseFacroty) {
            return new Guard($this->responseFacroty);
        });
    }
}