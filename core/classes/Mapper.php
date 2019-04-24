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
    protected $insertStmt;
    protected $updateStmt;
    protected $modelClass;

    private $where_array = [];
    private $or_where_array = [];

    private $limit = 0;

    private $order_by = "id DESC";

    public function __construct(string $modelClassName)
    {
        if(!class_exists($modelClassName) || !is_subclass_of($modelClassName, DomainObject::class)){
            throw new CoreException("Wrong model");
        }

        $this->modelClass = $modelClassName;

        $pdo = new DatabaseContex(DatabaseConfig::getStrategy());
        self::$PDO = $pdo->connect();
        $this->selectStmt = "SELECT * FROM ".$modelClassName::getTableName();
    }

    /**
     * @param int $id
     * @return DomainObject|null
     */
    public function findOne(int $id = 0): ?DomainObject
    {
        $old = $this->getFromMap($id);
        if (! is_null($old)) {
            return $old;
        }
        if($id > 0){
            $this->where_array[] = array(
                'field'=>'id',
                'condition'=>'=',
                'value'=>$id
            );
        }
        $this->doSelectStmt();
        foreach ($this->where_array as $item){
            $this->selectStmt->bindParam(":{$item['field']}", $item['value']);
        }
        foreach ($this->or_where_array as $item){
            $this->selectStmt->bindParam(":{$item['field']}", $item['value']);
        }

        $this->selectStmt->execute();
        $result = $this->selectStmt->fetch(\PDO::FETCH_ASSOC);
        /*var_dump($result);
        die();*/
        if(is_array($result)){
            $object = $this->createObject($result);
            return $object;
        }
        return null;
    }

    private function doSelectStmt()
    {
        $query_builder = $this->selectStmt;

        foreach ($this->where_array as $item){
            if(strpos($query_builder, 'WHERE')){
                $query_builder .= " AND {$item['field']}{$item['condition']}:{$item['field']}";
            } else {
                $query_builder .= " WHERE {$item['field']}{$item['condition']}:{$item['field']}";
            }
        }
        foreach ($this->or_where_array as $item){
            if(strpos($query_builder, 'WHERE')){
                $query_builder .= " OR {$item['field']}{$item['condition']}:{$item['field']}";
            } else {
                $query_builder .= " WHERE {$item['field']}{$item['condition']}:{$item['field']}";
            }
        }
        $query_builder .= " ORDER BY $this->order_by";

        $query_builder .= " LIMIT 1";

        $this->selectStmt = self::$PDO->prepare($query_builder);
    }

    private function doSelectAllStmt()
    {
        $query_builder = $this->selectStmt;

        foreach ($this->where_array as $item){
            if(strpos($query_builder, 'WHERE')){
                $query_builder .= " AND {$item['field']}{$item['condition']}:{$item['field']}";
            } else {
                $query_builder .= " WHERE {$item['field']}{$item['condition']}:{$item['field']}";
            }
        }
        foreach ($this->or_where_array as $item){
            if(strpos($query_builder, 'WHERE')){
                $query_builder .= " OR {$item['field']}{$item['condition']}:{$item['field']}";
            } else {
                $query_builder .= " WHERE {$item['field']}{$item['condition']}:{$item['field']}";
            }
        }
        $query_builder .= " ORDER BY $this->order_by";

        if($this->limit > 0){
            $query_builder .= " LIMIT $this->limit";
        }

        $this->selectStmt = self::$PDO->prepare($query_builder);
    }

    public function where(string $field, string $condition, string $value)
    {
        $this->where_array[] = array('field'=>$field, 'condition'=>$condition, 'value'=>$value);
        return $this;
    }

    public function orWhere(string $field, string $condition, string $value)
    {
        $this->or_where_array[] = array('field'=>$field,'condition'=>$condition,'value'=>$value);
        return $this;
    }

    public function limit(int $num)
    {
        $this->limit = $num;
        return $this;
    }

    public function orderBy(string $field, string $type)
    {
        $this->order_by = "$field $type";
        return $this;
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

        $className = $this->modelClass;
        $manyToOne = $className::manyToOne();
        if(is_array($manyToOne) && count($manyToOne)){
            foreach ($manyToOne as $field => $option){
                if(isset($raw[$option['binder']])){
                    $mapper = new self($option['model']);
                    $object = $mapper->findOne($raw[$option['binder']]);
                    /*var_dump($object);
                    die();*/
                    if($object){
                        $raw[$option['binder']] = $object;
                    }
                }
            }
        }
        $oneToMany = $className::oneToMany();
        if(is_array($oneToMany) && count($oneToMany)){
            foreach ($oneToMany as $field => $option){
                $mapper = new self($option['model']);
                $collection = $mapper->where($option['binder'], '=', $raw['id'])->findAll();
                $raw[$field] = $collection;
            }
        }

        $obj = new $this->modelClass($raw['id']);
        $obj = $obj->values($raw);
        $this->addToMap($obj);
        return $obj;
    }

    public function insert(DomainObject $domainObject)
    {
        $this->doInsertStmt();
        $className = $this->modelClass;
        foreach ($className::getFields() as $field){
            $get_method = 'get'.ucfirst($field);
            if($domainObject->{$get_method}() instanceof DomainObject){
                $this->insertStmt->bindParam(':'.$field, $domainObject->{$get_method}()->getId());
            } else {
                $this->insertStmt->bindParam(':'.$field, $domainObject->{$get_method}());
            }
        }
        $this->insertStmt->execute();
        $this->addToMap($domainObject);
    }

    private function doInsertStmt()
    {
        $modelClassName = $this->modelClass;
        $this->insertStmt = self::$PDO->prepare("INSERT INTO ".$modelClassName::getTableName()." (".implode(', ', $modelClassName::getFields()).") VALUES(:".implode(", :", $modelClassName::getFields()).")");
    }

    public function update(DomainObject $domainObject){
        $this->doUpdateStmt();
        $className = $this->modelClass;
        foreach ($className::getFields() as $field){
            $get_method = 'get'.ucfirst($field);
            if($domainObject->{$get_method}() instanceof DomainObject){
                $this->updateStmt->bindParam(':'.$field, $domainObject->{$get_method}()->getId());
            } else {
                $this->updateStmt->bindParam(':'.$field, $domainObject->{$get_method}());
            }

        }
        $this->updateStmt->bindParam(':id', $domainObject->getId());
        $this->updateStmt->execute();
        $this->addToMap($domainObject);
    }

    private function doUpdateStmt()
    {
        $modelClassName = $this->modelClass;
        $update_stmt_array = [];
        foreach ($modelClassName::getFields() as $field){
            $update_stmt_array[] = $field.'=:'.$field;
        }

        $this->updateStmt = self::$PDO->prepare("UPDATE ".$modelClassName::getTableName()." SET ".implode(', ', $update_stmt_array)." WHERE id=:id");
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        $this->doSelectAllStmt();
        foreach ($this->where_array as $item){
            $this->selectStmt->bindParam(":{$item['field']}", $item['value']);
        }
        foreach ($this->or_where_array as $item){
            $this->selectStmt->bindParam(":{$item['field']}", $item['value']);
        }
        $this->selectStmt->execute();
        $data = $this->selectStmt->fetchAll(\PDO::FETCH_ASSOC);
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