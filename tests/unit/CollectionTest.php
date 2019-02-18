<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 17.02.2019
 * Time: 16:59
 */
namespace Tests\Unit;

class CollectionTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructorException()
    {
        $this->expectException(\Core\Exceptions\CoreException::class);
        $collection = new \Core\Classes\Collection(['1','2']);
    }

}