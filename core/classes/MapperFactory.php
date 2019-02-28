<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-28
 * Time: 3:42 PM
 */
namespace Core\Classes;

class MapperFactory
{

    public function getMapper(string $modelName): Mapper
    {
        return new Mapper($modelName);
    }

}