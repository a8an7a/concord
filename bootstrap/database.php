<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'moosvadl_concord',
    'username' => 'moosvadl_concord',
    'password' => 'cB7b%2n2',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci'
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();