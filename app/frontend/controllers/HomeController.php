<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 08.02.2019
 * Time: 14:50
 */
namespace App\Frontend\Controllers;

use Core\Classes\Commands\ControllerCommand;
use App\Models\News;
use Core\Classes\Mapper;

class HomeController extends ControllerCommand
{

    public function actionIndex()
    {
        $title = 'Hello World';
        $description = 'Site description';

        $news = new Mapper(News::class);
        $news = $news->findAll();

        $this->content = $this->viewHelper->render('index', array(
            'title' => $title,
            'description' => $description,
            'news' => $news
        ));
    }

}