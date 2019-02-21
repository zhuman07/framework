<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-22
 * Time: 2:08 AM
 */
namespace Core\Classes\Database;

use Core\Classes\Database\Strategies\PostgresStrategy;
use Core\Exceptions\CoreException;

class DatabaseConfig
{

    /**
     * @return DatabaseStrategy
     * @throws CoreException
     */
    public static function getStrategy(): DatabaseStrategy
    {
        $db_config_path = ROOT.'configs/database.php';
        if(!file_exists($db_config_path)){
            throw new CoreException('file doesn\'t exist');
        }
        $db_config = require($db_config_path);
        $strategy = null;
        switch ($db_config['type']){
            case 'pgsql':
                $strategy = new PostgresStrategy($db_config['connection']);
                break;
            default:
                $strategy = new PostgresStrategy($db_config['connection']);
                break;
        }
        return $strategy;
    }

}