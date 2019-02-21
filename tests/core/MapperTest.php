<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 2019-02-21
 * Time: 10:48 PM
 */
namespace Tests\Core;

use App\Models\News;
use Core\Classes\DomainObject;
use Core\Classes\Mapper;
use Core\Classes\ObjectWatcher;
use Core\Exceptions\CoreException;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{

    public function setUp(): void
    {
        $news = new News(111);
        $objectWatcher = ObjectWatcher::add($news);
    }

    public function testConstructorException()
    {
        $this->expectException(CoreException::class);
        $this->expectExceptionMessage("Wrong model class");
        $model = new Mapper("WrongClass");
    }

    public function testFindMethodReturnModelObject()
    {
        $newsMapper = new Mapper(News::class);
        $news = $newsMapper->find(111);
        $this->assertInstanceOf(DomainObject::class, $news);
    }

    public function testFindMethodReturnNull()
    {
        $newsMapper = new Mapper(News::class);
        $news = $newsMapper->find(111111111);
        $this->assertNull($news);
    }

}