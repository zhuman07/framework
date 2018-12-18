<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 15.12.2018
 * Time: 0:28
 */
namespace Core\Traits;

trait Base
{

    public function getData()
    {
        $data = array();
        foreach ($this->fields as $field){
            $data[$field] = property_exists(static::class, $this->{$field}) ? $this->{$field} : null;
        }
        return $data;
    }

}