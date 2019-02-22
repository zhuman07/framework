<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-22
 * Time: 7:06 PM
 */
namespace App\Models;

use Core\Classes\Collection;
use Core\Classes\DomainObject;

class NewsCategory extends DomainObject
{

    protected static $table_name = 'news_categories';

    protected static $fields = ['title', 'description'];

    public static function oneToMany(): array
    {
        return array(
            'news' => array(
                'model' => News::class,
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
     * @var Collection
     */
    protected $news;

    /*
     |------------------------------------------------------------------------
     |  Getters
     |------------------------------------------------------------------------
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Collection
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

}