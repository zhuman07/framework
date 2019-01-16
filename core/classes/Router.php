<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 15.12.2018
 * Time: 1:03
 */
namespace Core\Classes;

final class Router
{
    private $routes;

    private $uri;

    private $controller;

    private $action;

    private static $instance;

    private function __construct()
    {
        $this->uri = $this->getUri();
        $this->routes = $this->getRoutes();
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    private function getUri()
    {
        if(isset($_SERVER['REQUEST_URI'])){
            return $_SERVER['REQUEST_URI'];
        }
        return null;
    }

    private function getRoutes()
    {
        $route_file = ROOT.'/configs/routes.php';
        if(!file_exists($route_file)){
            /*throw new Exception('file doesn\'t exist');*/
            die('file doesn\'t exist');
        }
        //$array = yaml_parse($route_file);
        $array = require_once($route_file);
        return $array;
    }

    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function execute()
    {
        //echo var_dump($this->routes); some text
    }

}