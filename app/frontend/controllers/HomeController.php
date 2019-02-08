<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 08.02.2019
 * Time: 14:50
 */
namespace App\Frontend\Controllers;

use Core\Classes\Commands\ControllerCommand;

class HomeController extends ControllerCommand
{

    public function actionIndex()
    {

        $this->content = $this->viewHelper->render('index');
    }

}