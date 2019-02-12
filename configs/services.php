<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 18:31
 */
return array(
    'router' => \Core\Classes\Router::getInstance(),
    'viewHelper' => new \Core\Classes\ViewHelper(),
    'objectWatcher' => \Core\Classes\ObjectWatcher::instance(),
);