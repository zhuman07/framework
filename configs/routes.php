<?php

return array(
    '/' => [
        'controller' => 'Home',
        'action' => 'index',
    ],
    'news/view/{sef}/{id}' => [
        'controller' => 'News',
        'action' => 'view',
        'id' => '\d{1,}',
        'sef' => '\w{1,}'
    ],
);