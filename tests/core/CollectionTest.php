<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 17.02.2019
 * Time: 16:59
 */
namespace Tests\Core;

use App\Models\News;
use App\Models\User;
use Core\Classes\Collection;
use Core\Classes\Mapper;
use Core\Exceptions\CoreException;

class CollectionTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructorException()
    {
        $this->expectException(\Core\Exceptions\CoreException::class);
        $this->expectExceptionMessage('need Mapper to generate objects');
        $collection = new \Core\Classes\Collection(['1','2']);
    }

    public function testObjectAdding()
    {
        $news = new News(1);
        $news->setTitle('test');

        $news2 = new News(2);
        $news2->setTitle('test 2');

        $news3 = new News(3);
        $news3->setTitle('test 3');

        $mapper = new Mapper(News::class);
        $collection = new Collection([], $mapper);

        $collection->add($news);
        $collection->add($news2);
        $collection->add($news3);
        $this->assertCount(3, $collection);
    }

    public function testObjectTotal()
    {
        $news = new News(1);
        $news->setTitle('test');

        $news2 = new News(2);
        $news2->setTitle('test 2');

        $news3 = new News(3);
        $news3->setTitle('test 3');

        $mapper = new Mapper(News::class);
        $collection = new Collection([], $mapper);

        $collection->add($news);
        $collection->add($news2);
        $collection->add($news3);
        $this->assertEquals(3, $collection->getTotal());
    }

    public function testObjectAddingException()
    {
        $this->expectException(CoreException::class);
        $this->expectExceptionMessage("This is a ".News::class." collection");
        $news = new User(1);
        $news->setEmail('test');

        $mapper = new Mapper(News::class);
        $collection = new Collection([], $mapper);

        $collection->add($news);
    }

}