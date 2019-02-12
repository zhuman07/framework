<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 09.02.2019
 * Time: 22:17
 */
namespace Core\Classes;

abstract class DomainObject
{

    protected static $table_name;
    protected static $fields;

    final public function __construct(int $id = 0)
    {
        $this->id = $id;

        if($id <= 0){
            $this->markNew();
        }
    }

    public function __set($name, $value)
    {
        if(!property_exists($this, $name)){
            throw new \Exception("The property $name doesn't exist in class ".self::class);
        }
    }

    public static function getCollection()
    {
        return Collection::getCollection();
    }

    public static function getTableName()
    {
        return static::$table_name;
    }

    public static function getFields(): array
    {
        return static::$fields;
    }

    public function values(array $data)
    {
        foreach ($data as $field => $val){
            $this->{$field} = $val;
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)){
            throw new \Exception('Method '.$name.' doesn\'t exist');
        }
    }

    public function markNew()
    {
        ObjectWatcher::addNew($this);
    }

    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    public function markDirty()
    {
        ObjectWatcher::addDirty($this);
    }

    public function markClean()
    {
        ObjectWatcher::addClean($this);
    }

    public function getFinder()
    {
        return new Mapper(get_class($this));
    }

}