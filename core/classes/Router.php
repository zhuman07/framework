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

    private $params;

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

        $uri = $this->uri === "/" ? "/" : trim($this->uri, '/');

        foreach ($this->routes as $uriPattern => $path) {
            preg_match_all("#(\{[A-Za-z]{1,}\})#", $uriPattern, $matches);
            $uri_no_params = preg_replace("#(\/\{[A-Za-z]{1,}\})#", "", $uriPattern);
            $params = array();

            foreach ($matches[1] as $match){
                $params[] = str_replace(array('{', '}'), '', $match);
            }
            foreach ($params as $param){
                $uri_no_params .= "/(".$path[$param].")";
            }

            if(($uri === '/' && $uri_no_params === '/') || ($uri_no_params !== '/' && preg_match("#$uri_no_params#", $uri))) {
                preg_match_all("#$uri_no_params#", $uri, $param_matches);
                $assoc_params = array();
                foreach ($params as $key => $val){
                    $assoc_params[$val] = $param_matches[++$key][0];
                }
                $controllerName = $path['controller']."Controller";
                $controllerName = ucfirst($controllerName);
                $this->controller = $controllerName;
                $actionName = 'action'.ucfirst($path['action']);
                $this->action = $actionName;
                $this->params = $assoc_params;

                break;
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

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function param(string $key): ?string
    {
        if(!isset($this->params[$key])){
            return null;
        }
        return $this->params[$key];
    }

}