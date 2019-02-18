<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 08.02.2019
 * Time: 14:50
 */
namespace App\Frontend\Controllers;

use App\Models\News;
use Core\Classes\Mapper;
use Core\Classes\ObjectWatcher;

class HomeController extends BaseController
{

    public function actionIndex()
    {
        $title = 'Hello World';
        $description = 'Site description';

        /*$news = new Mapper(News::class);
        $news = $news->findAll();

        $someNews = new Mapper(News::class);
        $someNews = $someNews->find(4);
        $someNews->setTitle('updated title');
        $someNews->setDescription('updated description');

        $newMaterial = new News();
        $newMaterial->setValues(['title'=>'new mat', 'description'=>'new mat desc', 'date'=>'2019-01-29']);
        $this->serviceContainer->get('objectWatcher')->performOperations();*/

        $this->content = $this->viewHelper->render('index', array(
            'title' => $title,
            'description' => $description,
            //'news' => $news
        ));
    }

}