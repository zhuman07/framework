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

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $date;
    protected static $fields = ['title', 'description', 'date'];

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

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return new \DateTime($this->date);
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

    public function setDescription(string $description)
    {
        $this->description = $description;
        $this->markDirty();
    }

}