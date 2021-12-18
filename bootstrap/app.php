<?php

session_start();

use Slim\Factory\AppFactory;
use App\Views\FactoryExtension;
use League\Container\Container;
use Illuminate\Pagination\Paginator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;

require __DIR__ . '/../vendor/autoload.php';

Sentinel::instance(
    new SentinelBootstrapper(
        require (__DIR__ . '/../config/auth.php')
    )
);

require __DIR__ . '/database.php';

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/container.php';
require __DIR__ . '/controllers.php';
require __DIR__ . '/exceptions.php';
require __DIR__ . '/middleware.php';
require __DIR__ . '/validation.php';

require __DIR__ .  '/../routes/web.php';

LengthAwarePaginator::viewFactoryResolver(function () use($container) {
    return new FactoryExtension(
        $container->get('view')
    );
});

LengthAwarePaginator::defaultView('pagination/bootstrap.twig');