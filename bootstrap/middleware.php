<?php

use App\Middleware\FlashOldFormData;

$app->add('csrf');
$app->add(new FlashOldFormData($container->get('flash')));