<?php

use App\Models\News;
use App\Controllers\HomeController;
use App\Controllers\NewsController;
use App\Middleware\RedirectIfGuest;
use App\Controllers\AboutController;
use App\Controllers\AnswerController;
use App\Controllers\EventsController;
use App\Controllers\Auth\SignInController;
use App\Controllers\Auth\SignUpController;
use App\Controllers\Account\EditController;
use App\Controllers\Auth\SignOutController;
use App\Middleware\RedirectIfAuthenticated;
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

$app->get('/', HomeController::class)->setName('home');
$app->get('/about', AboutController::class)->setName('about');
$app->get('/news', NewsController::class)->setName('news');
$app->get('/events', EventsController::class)->setName('events');

$app->group('/media', function($route) {
    $route->get('/photogallery', PhotogalleryController::class)->setName('photogallery');
    $route->get('/videogallery', VideogalleryController::class)->setName('videogallery');
});

$app->get('/answer', AnswerController::class)->setName('answer');

$app->group('/auth', function($route) {
    $route->group('', function($route) {
        $route->get('/signin', SignInController::class . ':index')->setName('auth.signin');
        $route->post('/signin', SignInController::class . ':action');

        $route->get('/signup', SignUpController::class . ':index')->setName('auth.signup');
        $route->post('/signup', SignUpController::class . ':action');

        $route->group('/password', function($route) {
            $route->get('/recover', PasswordRecoverController::class . ':index')->setName('auth.password.recover');
            $route->post('/recover', PasswordRecoverController::class . ':action');

            $route->get('/reset', PasswordResetController::class . ':index')->setName('auth.password.reset');
            $route->post('/reset', PasswordResetController::class . ':action');
        });
    })
        ->add(RedirectIfAuthenticated::class);

    $route->post('/signout', SignOutController::class)->setName('auth.signout');
});

$app->group('/account', function($route) {
    $route->get('/profile', ProfileController::class)->setName('account.profile');
    
    $route->get('/edit', EditController::class . ':index')->setName('account.edit');
    $route->post('/edit', EditController::class . ':action');
    
    $route->get('/password', AccountPasswordController::class . ':index')->setName('account.password');
    $route->post('/password', AccountPasswordController::class . ':action');
})
    ->add(RedirectIfGuest::class);

$app->group('/cms', function($route) {

    $route->get('/news', CmsNewsController::class)->setName('cms.news');

    $route->group('/news', function($route) {
        $route->get('/create', CmsNewsCreateController::class . ':index')->setName('cms.news.create');
        $route->post('/create', CmsNewsCreateController::class . ':action');

        $route->get('/view/{id}', CmsNewsViewController::class)->setName('cms.news.view');

        $route->get('/edit/{id}', CmsNewsEditController::class . ':index')->setName('cms.news.edit');
        $route->post('/edit/{id}', CmsNewsEditController::class . ':action');

        $route->get('/delete/{id}', CmsNewsDeleteController::class)->setName('cms.news.delete');
    });
})
    ->add(RedirectIfGuest::class);