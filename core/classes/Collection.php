<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 10.02.2019
 * Time: 14:08
 */
namespace Core\Classes;

use Core\Exceptions\CoreException;

class Collection implements \Iterator
{

    /**
     * @var Mapper
     */
    protected $mapper;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var array
     */
    protected $raw = [];

    /**
     * @var int
     */
    private $pointer = 0;

    /**
     * @var array
     */
    private $objects = [];

    /**
     * @var bool
     */
    private $run = false;

    public function __construct(array $raw = [], Mapper $mapper = null)
    {
        $this->raw = $raw;
        $this->total = count($raw);
        if (count($raw) && is_null($mapper)) {
            throw new CoreException("need Mapper to generate objects");
        }
        $this->mapper = $mapper;
    }

    public function add(DomainObject $object)
    {
        $class = $this->targetClass();
        if (! ($object instanceof $class )) {
            throw new CoreException("This is a {$class} collection");
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    /*public function getGenerator()
    {
        for ($x = 0; $x < $this->total; $x++) {
            yield $this->getRow($x);
        }
    }*/

    /**
     * @param $num
     * @return mixed|null
     */
    private function getRow($num): ?DomainObject
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->mapper->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

    public function notifyAccess()
    {
        if (! $this->run) {
            /*$this->stmt->execute($this->valueArray);
            $this->raw = $this->stmt->fetchAll();
            $this->total = count($this->raw);*/
        }
        $this->run = true;
    }

    /**
     * @return null|string
     */
    protected function targetClass(): ?string
    {
        return $this->mapper ? $this->mapper->getModelClassName() : null;
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);
        if (! is_null($row)) {
            $this->pointer++;
        }
    }

    public function valid()
    {
        return (! is_null($this->current()));
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

}