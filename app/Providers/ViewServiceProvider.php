<?php

namespace App\Providers;

use Slim\Views\Twig;
use App\Views\CsrfExtension;
use Slim\Views\TwigExtension;
use App\Views\FactoryExtension;
use Slim\Psr7\Factory\UriFactory;
use Slim\Views\TwigRuntimeLoader;
use Slim\Interfaces\RouteParserInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'view'
    ];

    protected $routeParser;

    public function __construct(RouteParserInterface $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function register()
    {
        $container = $this->getContainer();

        $container->add('view', function () use ($container) {
            $twig = new Twig(__DIR__ . '/../../resources/views', [
                'cache' => false
            ]);

            $twig->addRuntimeLoader(
                new TwigRuntimeLoader(
                    $this->routeParser,
                    (new UriFactory)->createFromGlobals($_SERVER)
                )
            );

            $this->registerGlobals($twig);
            $twig->addExtension(new TwigExtension());
            $twig->addExtension(new CsrfExtension($container->get('csrf')));

            return $twig;
        });
    }

    protected function registerGlobals(Twig $twig)
    {
        $container = $this->getContainer();
        
        $twig->getEnvironment()->addGlobal('user', Sentinel::check());
        $twig->getEnvironment()->addGlobal('status', $container->get('flash')->getFirstMessage('status'));
        $twig->getEnvironment()->addGlobal('errors', $container->get('flash')->getFirstMessage('errors'));
        $twig->getEnvironment()->addGlobal('old', $container->get('flash')->getFirstMessage('old'));
    }
}