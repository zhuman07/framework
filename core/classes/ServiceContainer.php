<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 18:12
 */
namespace Core\Classes;

use Core\Exceptions\CoreException;

class ServiceContainer
{

    /**
     * @var array|mixed
     */
    private $services = [];

    public function __construct()
    {
        $this->services = require(ROOT . "configs/services.php");
    }

    /**
     * @param string $serviceName
     * @return mixed
     * @throws \Exception
     */
    public function get(string $serviceName)
    {
        if (!isset($this->services[$serviceName])) {
            throw new CoreException("unknown component '$serviceName'");
        }
        $ret = $this->services[$serviceName];
        return $ret;
    }

}