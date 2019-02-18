<?php

return array(
    '/' => [
        'controller' => 'Home',
        'action' => 'index',
    ],
    'news/{sef}/{id}' => [
        'controller' => 'News',
        'action' => 'view',
        'id' => '\d{1,}',
        'sef' => '\w{1,}'
    ],
    'news/{sef}' => [
        'controller' => 'News',
        'action' => 'list',
        'sef' => '\w{1,}'
    ],
);