<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 08.02.2019
 * Time: 14:50
 */
namespace App\Frontend\Controllers;

class NewsController extends BaseController
{

    public function actionView()
    {
        $title = 'News №12';
        $description = 'About news';

        echo $this->router->param('id')." ".$this->router->param('sef');

        $this->content = $this->viewHelper->render('news/view', array(
            'title' => $title,
            'description' => $description
        ));
    }

    public function actionList()
    {
        $title = 'News List';
        $description = 'About news list';

        echo $this->router->param('sef');

        $this->content = $this->viewHelper->render('news/view', array(
            'title' => $title,
            'description' => $description
        ));
    }

}