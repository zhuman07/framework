<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-22
 * Time: 2:21 AM
 */
namespace Core\Classes\Database;

interface DatabaseStrategy
{

    /**
     * @return \PDO
     */
    public function doConnect(): \PDO;

}