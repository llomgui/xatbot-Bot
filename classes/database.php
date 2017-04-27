<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$infos = json_decode(file_get_contents('config.json'), true)['database'];
$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => $infos['driver'],
    'host'      => $infos['host'],
    'database'  => $infos['database'],
    'username'  => $infos['username'],
    'password'  => $infos['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'strict'    => false,
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();