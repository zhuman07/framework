<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 11.02.2019
 * Time: 1:25
 */
namespace App\Models;
use Core\Classes\DomainObject;

class Role extends DomainObject
{

    /**
     * @var string
     */
    protected static $table_name = 'system_roles';

    /**
     * @var array
     */
    protected static $fields = ['title'];

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /*
     |---------------------------------------------------------------------
     |  Getters
     |---------------------------------------------------------------------
     */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /*
     |-------------------------------------------------------------------
     |  Setters
     |-------------------------------------------------------------------
     */

    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->markDirty();
    }

}