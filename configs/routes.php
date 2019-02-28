<?php

return array(
    'home' => [
        'path' => '/',
        'controller' => \App\Frontend\Controllers\HomeController::class,
        'action' => 'index',
    ],
    'news.view' => [
        'path' => 'news/{sef}/{id}',
        'controller' => \App\Frontend\Controllers\NewsController::class,
        'action' => 'view',
        'id' => '\d{1,}',
        'sef' => '\w{1,}'
    ],
    'news.list' => [
        'path' => 'news/{cat}',
        'controller' => \App\Frontend\Controllers\NewsController::class,
        'action' => 'list',
        'cat' => '\w{1,}'
    ],
);