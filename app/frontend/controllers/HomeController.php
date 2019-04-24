<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 08.02.2019
 * Time: 14:50
 */
namespace App\Frontend\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Core\Classes\Mapper;
use Core\Classes\ObjectWatcher;

class HomeController extends BaseController
{

    public function index()
    {
        $title = 'Hello World';
        $description = 'Site description';

        /*$news = new Mapper(News::class);
        $news = $news->findAll();*/

        $newsCategory = $this->mapper->getMapper(NewsCategory::class);
        $newsCategory = $newsCategory->findOne(7);

        $news = $newsCategory->getNews();

        /*$newsCategory = new Mapper(NewsCategory::class);
        $newsCategory = $newsCategory->find(7);

        $someNews = new Mapper(News::class);
        $someNews = $someNews->find(4);
        $someNews->setTitle('Test new logic by updating');
        $someNews->setDescription('Test new logic by updating');
        $someNews->setCategory($newsCategory);

        $newMaterial = new News();
        $newMaterial->values(['title'=>'Test new logic by inserting', 'description'=>'Test new logic by inserting', 'date'=>'2019-02-23']);
        $this->serviceContainer->get('objectWatcher')->performOperations();*/

        $this->content = $this->viewHelper->render('index', array(
            'title' => $title,
            'description' => $description,
            'news' => $news
        ));
    }

}