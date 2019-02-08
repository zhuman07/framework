<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 18:12
 */
namespace Core\Classes;

class ServiceContainer
{

    private $services = [];

    public function __construct()
    {
        $this->services = require_once(ROOT . "configs/services.php");
    }

    public function get(string $class)
    {
        if (!isset($this->services[$class])) {
            throw new \Exception("unknown component '$class'");
        }
        $ret = $this->services[$class];
        return $ret;
    }

}