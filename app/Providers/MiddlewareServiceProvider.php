<?php

namespace App\Providers;

use App\Middleware\RedirectIfGuest;
use Slim\Interfaces\RouteParserInterface;
use App\Middleware\RedirectIfAuthenticated;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MiddlewareServiceProvider extends AbstractServiceProvider
{
    protected $routeParser;

    protected $provides = [
        RedirectIfGuest::class
    ];

    public function __construct(RouteParserInterface $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function register()
    {
        $container = $this->getContainer();
        
        $container->add(RedirectIfGuest::class, function() use($container) {
            return new RedirectIfGuest(
                $container->get('flash'),
                $this->routeParser
            );
        });

        $container->add(RedirectIfAuthenticated::class, function() {
            return new RedirectIfAuthenticated();
        });
    }
}