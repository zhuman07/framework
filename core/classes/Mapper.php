<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 10.02.2019
 * Time: 14:11
 */
namespace Core\Classes;

class Mapper
{

    protected static $PDO;
    protected $selectStmt;
    protected $selectAllStmt;
    protected $modelClass;

    public function __construct(string $modelClassName)
    {
        $this->modelClass = $modelClassName;
        $model = new $modelClassName();
        self::$PDO = new \PDO('pgsql:host=127.0.0.1;port=5432;dbname=testdb', 'postgres', 'secret');
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM ".$model->getTableName()." WHERE id=?");
        $this->selectAllStmt = self::$PDO->prepare("SELECT * FROM ".$model->getTableName());



        return $this;
    }

    public function find(int $id): DomainObject
    {
        $old = $this->getFromMap($id);
        if (! is_null($old)) {
            return $old;
        }
        $this->selectStmt->execute(array($id));
        $array = $this->selectStmt->fetch(\PDO::FETCH_ASSOC);

        $object = $this->createObject($array);
        return $object;
    }

    public function createObject(array $raw): DomainObject
    {
        $old = $this->getFromMap($raw['id']);
        if (! is_null($old)) {
            return $old;
        }
        $obj = new $this->modelClass($raw['id']);
        $obj = $obj->setValues($raw);
        $this->addToMap($obj);
        return $obj;
    }

    public function insert(DomainObject $domainObject)
    {
        $this->doInsert($domainObject);
        $this->addToMap($domainObject);
    }

    public function findAll(): Collection
    {
        $this->selectAllStmt->execute();
        $data = $this->selectAllStmt->fetchAll(\PDO::FETCH_ASSOC);
        /*var_dump($data);
        die();*/
        return new Collection($data, $this);
    }

    public function getModelClassName(): string
    {
        return $this->modelClass;
    }

    private function getFromMap($id)
    {
        return ObjectWatcher::exists(
            $this->getModelClassName(),
            $id
        );
    }

    private function addToMap(DomainObject $obj)
    {
        ObjectWatcher::add($obj);
    }

}