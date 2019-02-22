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

    /**
     * @var string
     */
    protected static $table_name = 'news';

    /**
     * @var array
     */
    protected static $fields = ['title', 'description', 'date', 'category'];

    public static function manyToOne(): array
    {
        return array(
            'category' => array(
                'model' => NewsCategory::class,
                'binder' => 'category'
            )
        );
    }

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

    /**
     * @var NewsCategory
     */
    protected $category;

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
     * @return string
     */
    public function getDate(): string
    {
        $date = new \DateTime($this->date);
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return NewsCategory
     */
    public function getCategory(): ?NewsCategory
    {
        return $this->category;
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

    public function setCategory(NewsCategory $category)
    {
        $this->category = $category;
        $this->markDirty();
    }

}