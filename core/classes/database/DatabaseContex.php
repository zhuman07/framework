<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-22
 * Time: 2:18 AM
 */
namespace Core\Classes\Database;

class DatabaseContex
{

    private $databaseStrategy;

    public function __construct(DatabaseStrategy $databaseStrategy)
    {
        $this->databaseStrategy = $databaseStrategy;
    }

    public function connect(): \PDO
    {
        return $this->databaseStrategy->doConnect();
    }

}