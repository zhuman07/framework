<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 11.02.2019
 * Time: 1:25
 */
namespace App\Models;
use Core\Classes\DomainObject;

class News extends DomainObject
{
    protected static $table_name = 'news';

    protected $id;
    protected $title;
    protected $description;
    protected $date;
    protected static $fields = ['title', 'description', 'date'];

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->markDirty();
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        $this->markDirty();
    }

}