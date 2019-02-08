<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 17:28
 */
namespace Core\Classes;

abstract class Command
{

    public function execute()
    {
        $this->doExecute();
    }

    abstract protected function doExecute();

}