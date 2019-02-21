<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 10.02.2019
 * Time: 14:11
 */
namespace Core\Classes;

use Core\Classes\Database\DatabaseConfig;
use Core\Classes\Database\DatabaseContex;
use Core\Exceptions\CoreException;

class Mapper
{

    /**
     * @var \PDO
     */
    protected static $PDO;

    /**
     * @var \PDOStatement
     */
    protected $selectStmt;
    protected $selectAllStmt;
    protected $insertStmt;
    protected $updateStmt;
    protected $modelClass;

    public function __construct(string $modelClassName)
    {
        if(!is_subclass_of($modelClassName, DomainObject::class)){
            throw new CoreException("Wrong model class");
        }

        $this->modelClass = $modelClassName;

        $pdo = new DatabaseContex(DatabaseConfig::getStrategy());
        self::$PDO = $pdo->connect();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM ".$modelClassName::getTableName()." WHERE id=?");
        $this->selectAllStmt = self::$PDO->prepare("SELECT * FROM ".$modelClassName::getTableName());
        $this->insertStmt = self::$PDO->prepare("INSERT INTO ".$modelClassName::getTableName()." (".implode(', ', $modelClassName::getFields()).") VALUES(:".implode(", :", $modelClassName::getFields()).")");

        $update_stmt_array = [];
        foreach ($modelClassName::getFields() as $field){
            $update_stmt_array[] = $field.'=:'.$field;
        }

        $this->updateStmt = self::$PDO->prepare("UPDATE ".$modelClassName::getTableName()." SET ".implode(', ', $update_stmt_array)." WHERE id=:id");
    }

    /**
     * @param int $id
     * @return DomainObject|null
     */
    public function find(int $id): ?DomainObject
    {
        $old = $this->getFromMap($id);
        if (! is_null($old)) {
            return $old;
        }
        $this->selectStmt->execute(array($id));
        $result = $this->selectStmt->fetch(\PDO::FETCH_ASSOC);

        if(is_array($result)){
            $object = $this->createObject($result);
            return $object;
        }
        return null;
    }

    /**
     * @param array $raw
     * @return DomainObject
     */
    public function createObject(array $raw): DomainObject
    {
        $old = $this->getFromMap($raw['id']);
        if (! is_null($old)) {
            return $old;
        }
        $obj = new $this->modelClass($raw['id']);
        $obj = $obj->values($raw);
        $this->addToMap($obj);
        return $obj;
    }

    public function insert(DomainObject $domainObject)
    {
        $className = $this->modelClass;
        foreach ($className::getFields() as $field){
            $this->insertStmt->bindParam(':'.$field, $domainObject->{'get'.ucfirst($field)}());
        }
        $this->insertStmt->execute();
        $this->addToMap($domainObject);
    }

    public function update(DomainObject $domainObject){
        $className = $this->modelClass;
        foreach ($className::getFields() as $field){
            $this->updateStmt->bindParam(':'.$field, $domainObject->{'get'.ucfirst($field)}());
        }
        $this->updateStmt->bindParam(':id', $domainObject->getId());
        $this->updateStmt->execute();
        $this->addToMap($domainObject);
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        $this->selectAllStmt->execute();
        $data = $this->selectAllStmt->fetchAll(\PDO::FETCH_ASSOC);
        return new Collection($data, $this);
    }

    /**
     * @return string
     */
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