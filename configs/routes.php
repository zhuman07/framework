<?php

return array(
    'home' => [
        'path' => '/',
        'controller' => \App\Frontend\Controllers\HomeController::class,
        'action' => 'index',
    ],
    'news.list' => [
        'path' => 'news/{cat}',
        'controller' => \App\Frontend\Controllers\NewsController::class,
        'action' => 'list',
        'cat' => '[0-9]{1,}'
    ],
    'news.view' => [
        'path' => 'news/{sef}/{id}',
        'controller' => \App\Frontend\Controllers\NewsController::class,
        'action' => 'view',
        'id' => '[0-9]{1,}',
        'sef' => '[a-z]{1,}'
    ],

    'admin' => [
        'path' => 'admin(/{model}/{action}(/{id}(/{id2})))',
        'controller' => \App\Backend\Controllers\AdminController::class,
        'model' => '\w{1,}',
        'action' => '\w{1,}',
        'id' => '\d{1,}',
        'id2' => '\d{1,}',
    ],
    /*'admin' => [
        'path' => 'admin',
        'controller' => \App\Backend\Controllers\AdminController::class,
        'action' => 'index',
    ],
    'admin.list' => [
        'path' => 'admin/{model}/{action}',
        'controller' => \App\Backend\Controllers\AdminController::class,
        'model' => '\w{1,}',
        'action' => 'list',
    ],*/

);