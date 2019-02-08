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
        if(!isset($_SERVER['REQUEST_URI'])) {
            $this->uri = trim($_SERVER['REQUEST_URI'], '/');
        } else {
            $this->uri = $_SERVER['REQUEST_URI'];
        }

        $route_file = ROOT.'configs/routes.php';
        if(!file_exists($route_file)){
            /*throw new Exception('file doesn\'t exist');*/
            die('file doesn\'t exist');
        }
        //$array = yaml_parse($route_file);
        $routes_array = require_once($route_file);
        $this->routes = $routes_array;

        $uri = $this->uri;
        foreach ($this->routes as $uriPattern => $path) {

            if(preg_match("#$uriPattern#", $uri)) {
                $internalRouter = preg_replace("#$uriPattern#", $path['path'], $uri);
                $segments = explode("/", $internalRouter);
                $controllerName = $path['controller']."Controller";
                $controllerName = ucfirst($controllerName);

                $this->controller = $controllerName;
                $actionName = 'action'.ucfirst($path['action']);
                $this->action = $actionName;
                /*$parametrs = $segments;
                $controllerFile = ROOT."/controllers/".$controllerName.".php";
                if(file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject, $actionName), $parametrs);
                if($result != null) {
                    break;
                }*/
            }
        }
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

}