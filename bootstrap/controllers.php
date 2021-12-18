<?php

use App\Controllers\HomeController;
use App\Controllers\NewsController;
use App\Controllers\AboutController;
use App\Controllers\AnswerController;
use App\Controllers\EventsController;
use App\Controllers\Auth\SignInController;
use App\Controllers\Auth\SignUpController;
use App\Controllers\Account\EditController;
use App\Controllers\Auth\SignOutController;
use App\Controllers\Account\ProfileController;
use App\Controllers\Cms\News\CmsNewsController;
use App\Controllers\Media\PhotogalleryController;
use App\Controllers\Media\VideogalleryController;
use App\Controllers\Cms\News\CmsNewsEditController;
use App\Controllers\Cms\News\CmsNewsViewController;
use App\Controllers\Cms\News\CmsNewsCreateController;
use App\Controllers\Cms\News\CmsNewsDeleteController;
use App\Controllers\Account\AccountPasswordController;
use App\Controllers\Auth\Password\PasswordResetController;
use App\Controllers\Auth\Password\PasswordRecoverController;

$container->add(HomeController::class, function () use ($container) {
    return new HomeController(
        $container->get('view')
    );
});

$container->add(AboutController::class, function () use ($container) {
    return new AboutController(
        $container->get('view')
    );
});

$container->add(NewsController::class, function () use ($container) {
    return new NewsController(
        $container->get('view')
    );
});

$container->add(EventsController::class, function () use ($container) {
    return new EventsController(
        $container->get('view')
    );
});

$container->add(PhotogalleryController::class, function () use ($container) {
    return new PhotogalleryController(
        $container->get('view')
    );
});

$container->add(VideogalleryController::class, function () use ($container) {
    return new VideogalleryController(
        $container->get('view')
    );
});

$container->add(AnswerController::class, function () use ($container) {
    return new AnswerController(
        $container->get('view')
    );
});

$container->add(SignInController::class, function () use ($app, $container) {
    return new SignInController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(SignOutController::class, function () use ($app) {
    return new SignOutController(
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(SignUpController::class, function () use ($app, $container) {
    return new SignUpController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(PasswordRecoverController::class, function () use ($app, $container) {
    return new PasswordRecoverController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser(),
        $container->get('mail')
    );
});

$container->add(PasswordResetController::class, function () use ($app, $container) {
    return new PasswordResetController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(ProfileController::class, function () use ($container) {
    return new ProfileController(
        $container->get('view')
    );
});

$container->add(EditController::class, function () use ($app, $container) {
    return new EditController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(AccountPasswordController::class, function () use ($app, $container) {
    return new AccountPasswordController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(CmsNewsCreateController::class, function () use ($app, $container) {
    return new CmsNewsCreateController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(CmsNewsViewController::class, function () use ($container) {
    return new CmsNewsViewController(
        $container->get('view')
    );
});

$container->add(CmsNewsEditController::class, function () use ($app, $container) {
    return new CmsNewsEditController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(CmsNewsController::class, function () use ($app, $container) {
    return new CmsNewsController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(CmsNewsDeleteController::class, function () use ($app, $container) {
    return new CmsNewsDeleteController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});