<?php

defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(__FILE__)));
$autolaod = ROOT_PATH . '/vendor/autoload.php';
if (is_file($autolaod)) {
    require $autolaod;
}

use MagmaCore\Middleware\Middleware;
use MagmaCore\Middleware\BeforeMiddleware;
use MagmaCore\Middleware\AfterMiddleware;

$object = new StdClass;
$object->runs = [];

$middleware = new Middleware();
$end = $middleware->middlewares(
    [
        new AfterMiddleware(),
        new BeforeMiddleware(),
        new AfterMiddleware(),
        new BeforeMiddleware()
    ]
)->middleware($object, function($object) {
    $object->runs[] = 'core';
    return $object;
});

var_dump($end);