<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 08.02.2019
 * Time: 14:50
 */
namespace App\Frontend\Controllers;

use Core\Classes\Commands\ControllerCommand;

class NewsController extends ControllerCommand
{

    public function actionView()
    {
        $title = 'News №12';
        $description = 'About news';

        echo $this->router->param('id')." ".$this->router->param('sef');
        //die();

        $this->content = $this->viewHelper->render('news/view', array(
            'title' => $title,
            'description' => $description
        ));
    }

}