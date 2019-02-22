<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 09.02.2019
 * Time: 22:17
 */
namespace Core\Classes;

use Core\Exceptions\CoreException;

abstract class DomainObject
{

    /**
     * @var string
     */
    protected static $table_name;

    /**
     * @var array
     */
    protected static $fields;

    public function __construct(int $id = 0)
    {
        $this->id = $id;

        if($id === 0){
            $this->markNew();
        }
    }

    public function __set($name, $value)
    {
        if(!property_exists($this, $name)){
            throw new \Exception("The property $name doesn't exist in class ".self::class);
        }
    }


    /**
     * @return Collection
     */
    public static function getCollection(): Collection
    {
        return Collection::getCollection();
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$table_name;
    }

    /**
     * @return array
     */
    public static function getFields(): array
    {
        return static::$fields;
    }

    public static function manyToOne(): array
    {
        return array(

        );
    }

    public static function oneToMany(): array
    {
        return array(

        );
    }

    /**
     * @param array $data
     * @return $this
     * @throws CoreException
     */
    public function values(array $data)
    {
        foreach ($data as $field => $val){
            if(!property_exists($this, $field)){
                throw new CoreException("the property $field doesn't exist in class ".get_class($this));
            }
            $this->{$field} = $val;
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)){
            throw new \Exception('The method '.$name.' doesn\'t exist in the '.get_class($this).' class');
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

    /**
     * @return Mapper
     */
    public function getFinder(): Mapper
    {
        return new Mapper(get_class($this));
    }

}