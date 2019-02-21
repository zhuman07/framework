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
        header('Content-Type: application/json');
        $this->render = false;
        $title = 'News List';
        $description = 'About news list';

        $arr = array('route_name' => $this->router->getRoute());

        echo json_encode($arr);

        /*$this->content = $this->viewHelper->render('news/view', array(
            'title' => $title,
            'description' => $description
        ));*/
    }

}