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
    /**
     * @var array
     */
    private $routes;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $params;

    /**
     * @var string
     */
    private $current_route;

    /**
     * @var array
     */
    private $not_required_params;

    /**
     * @var array
     */
    private $required_params;

    /**
     * @var string
     */
    private $uri_without_params;

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

        foreach ($this->routes as $route_name => $route_options) {
            $this->getNotRequiredParam($route_options['path']);
            $this->getRequiredParam($route_options['path']);
            $uri_no_params = preg_replace("#(\/\{[A-Za-z0-9]{1,}\})#", "",  $route_options['path']);

            $uri_no_params = str_replace(array('(', ')'), '', $uri_no_params);

            $this->setUriWithoutParams($uri, $uri_no_params, $route_options);

            /*foreach ($this->required_params as $param){
                $uri_no_params .= "/(".$route_options[$param].")";
            }
            foreach ($this->not_required_params as $param){
                $uri_no_params .= "/(".$route_options[$param].")";
            }*/
            if(!is_null($this->uri_without_params) && $this->isCorrectUri($uri, $this->uri_without_params)) {
                preg_match_all("#$this->uri_without_params#", $uri, $param_matches);
                $params = array_merge($this->required_params, $this->not_required_params);

                $assoc_params = array();
                foreach ($params as $key => $val){
                    $assoc_params[$val] = $param_matches[++$key][0];
                }
                //Debug::dump($assoc_params);
                $controllerName = $route_options['controller'];
                $controllerName = ucfirst($controllerName);
                $this->controller = $controllerName;
                $actionName = $route_options['action'] ? $route_options['action'] : 'index';
                $this->action = $actionName;
                $this->params = $assoc_params;
                $this->current_route = $route_name;
                break;
            }

        }
    }

    private function setUriWithoutParams(string $uri, string $uri_no_params, array $route_options)
    {
        if(($uri === '/' && $uri_no_params === '/') || ($uri_no_params !== '/' && preg_match("#$uri_no_params#", $uri))){
            $this->uri_without_params = $uri_no_params;
        }

        foreach ($this->required_params as $param){
            $uri_no_params .= "/(".$route_options[$param].")";
            if($uri_no_params !== '/' && preg_match("#$uri_no_params#", $uri)){
                $this->uri_without_params = $uri_no_params;
                //break;
            }
        }

        foreach ($this->not_required_params as $param){
            $uri_no_params .= "/(".$route_options[$param].")";
            if($uri_no_params !== '/' && preg_match("#$uri_no_params#", $uri)){
                $this->uri_without_params = $uri_no_params;
                //break;
            }
        }
    }

    private function isCorrectUri(string $uri, string $uri_no_params): bool
    {
        return ($uri === '/' && $uri_no_params === '/') || ($uri_no_params !== '/' && preg_match("#$uri_no_params#", $uri));
    }

    private function getRequiredParam(string $path): array
    {
        preg_match_all("#(\{[A-Za-z0-9]{1,}\})#", $path, $matches);
        $params = [];
        $filtered_params = array_map(function ($item){
            return str_replace(array('{', '}'), '', $item);
        }, $matches[1]);
        foreach ($filtered_params as $param){
            if(!in_array($param, $this->not_required_params)){
                $params[] = $param;
            }
        }
        $this->required_params = $params;
        return $params;
    }

    private function getNotRequiredParam(string $path): array
    {
        preg_match_all("#\((.*)\)#", $path, $required_params);

        $str = "";
        if(!empty($required_params[1][0])){
            $str = $required_params[1][0];
        }
        preg_match_all("#(\{[A-Za-z0-9]{1,}\})#", $str, $matches);
        $params = array_map(function($item){
            return str_replace(array('{', '}'), '', $item);
        }, $matches[1]);
        $this->not_required_params = $params;
        return $params;
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

    private function getRoutes()
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

    /**
     * @return null|string
     */
    public function getRoute(): ?string
    {
        return $this->current_route;
    }

    /**
     * @return null|string
     */
    public function getController(): ?string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function param(string $key): ?string
    {
        if(!isset($this->params[$key])){
            return null;
        }
        return $this->params[$key];
    }

}