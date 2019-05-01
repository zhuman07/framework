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
    /**
     * @var array
     */
    private $all = [];

    /**
     * @var array
     */
    private $dirty = [];

    /**
     * @var array
     */
    private $new = [];

    /**
     * @var array
     */
    private $delete = [];

    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return ObjectWatcher
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }

    /**
     * @param DomainObject $obj
     * @return string
     */
    public function globalKey(DomainObject $obj): string
    {
        $key = get_class($obj) . "." . $obj->getId();
        return $key;
    }

    /**
     * @param DomainObject $obj
     */
    public static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    /**
     * @param string $classname
     * @param int $id
     * @return DomainObject|null
     */
    public static function exists(string $classname, int $id): ?DomainObject
    {
        $inst = self::instance();
        $key = "{$classname}.{$id}";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }

    /**
     * @param DomainObject $obj
     */
    public static function addDelete(DomainObject $obj): void
    {
        $inst = self::instance();
        $inst->delete[$inst->globalKey($obj)] = $obj;
    }

    /**
     * @param DomainObject $obj
     */
    public static function addDirty(DomainObject $obj): void
    {
        $inst = self::instance();
        if (! in_array($obj, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    /**
     * @param DomainObject $obj
     */
    public static function addNew(DomainObject $obj): void
    {
        $inst = self::instance();
        $inst->new[] = $obj;
    }

    /**
     * @param DomainObject $obj
     */
    public static function addClean(DomainObject $obj): void
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

    public function performOperations(): void
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