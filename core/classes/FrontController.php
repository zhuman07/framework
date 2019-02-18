<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 17:18
 */
namespace Core\Classes;

final class FrontController
{

    private $serviceContainer;

    private function __construct()
    {
        $this->serviceContainer = new ServiceContainer();
    }

    public static function run()
    {
        $instance = new FrontController();
        $instance->init();
    }

    private function init()
    {
        $router = $this->serviceContainer->get('router');
        $controllerName = $router->getController();

        $controller = "\\App\\Frontend\\Controllers\\".$controllerName;
        if(!class_exists($controller)){
            throw new \Exception('Controller not found');
        }
        //$controller = require_once($controllerPath);
        $controller = new $controller($this->serviceContainer);
        $controller->execute();
    }

}