<?php

return array(
    'home' => [
        'path' => '/',
        'controller' => 'Home',
        'action' => 'index',
    ],
    'news.view' => [
        'path' => 'news/{sef}/{id}',
        'controller' => 'News',
        'action' => 'view',
        'id' => '\d{1,}',
        'sef' => '\w{1,}'
    ],
    'news.list' => [
        'path' => 'news/{cat}',
        'controller' => 'News',
        'action' => 'list',
        'cat' => '\w{1,}'
    ],
);