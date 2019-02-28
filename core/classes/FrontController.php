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

    /**
     * @var ServiceContainer
     */
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
        $controller = $router->getController();
        if(!class_exists($controller)){
            throw new \Exception("Controller $controller not found");
        }
        $controller = new $controller($this->serviceContainer);
        $controller->execute();
    }

}