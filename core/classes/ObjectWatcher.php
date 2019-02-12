<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 11.02.2019
 * Time: 16:10
 */
namespace Core\Classes;

class ObjectWatcher
{
    private $all = [];
    private $dirty = [];
    private $new = [];
    private $delete = [];
    private static $instance = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }

    public function globalKey(DomainObject $obj): string
    {
        $key = get_class($obj) . "." . $obj->getId();
        return $key;
    }

    public static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    public static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "{$classname}.{$id}";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }

    public static function addDelete(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->delete[$inst->globalKey($obj)] = $obj;
    }

    public static function addDirty(DomainObject $obj)
    {
        $inst = self::instance();
        if (! in_array($obj, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    public static function addNew(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->new[] = $obj;
    }

    public static function addClean(DomainObject $obj)
    {
        $inst = self::instance();
        unset($inst->delete[$inst->globalKey($obj)]);
        unset($inst->dirty[$inst->globalKey($obj)]);
        $inst->new = array_filter(
            $inst->new,
            function ($a) use ($obj) {
                return !($a === $obj);
            }
        );
    }

    public function performOperations()
    {
        /*var_dump($this->dirty);
        die();*/
        foreach ($this->dirty as $key => $obj) {
            $obj->getFinder()->update($obj);
            print "updating " . $obj->getId() . "\n";
        }
        foreach ($this->new as $key => $obj) {
            $obj->getFinder()->insert($obj);
            print "inserting " . $obj->getId() . "\n";
        }
        $this->dirty = [];
        $this->new = [];
    }

}