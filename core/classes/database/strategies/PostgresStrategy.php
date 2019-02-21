<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-22
 * Time: 2:28 AM
 */

namespace Core\Classes\Database\Strategies;

use Core\Classes\Database\DatabaseStrategy;

class PostgresStrategy implements DatabaseStrategy
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * PostgresStrategy constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->host = $data['hostname'];
        $this->port = $data['port'];
        $this->dbname = $data['database'];
        $this->username = $data['username'];
        $this->password = $data['password'];
    }

    /**
     * @return \PDO
     */
    public function doConnect(): \PDO
    {
        return new \PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->username, $this->password);
    }

}