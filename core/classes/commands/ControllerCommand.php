<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 17:31
 */
namespace Core\Classes\Commands;

use Core\Classes\Command;
use Core\Classes\Router;
use Core\Classes\ServiceContainer;
use Core\Classes\ViewHelper;

class ControllerCommand extends Command
{
    protected $serviceContainer;
    protected $template;
    protected $content;
    protected $render = true;
    protected $router;
    protected $viewHelper;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->router = $serviceContainer->get('router');
        $this->viewHelper = $serviceContainer->get('viewHelper');
    }

    protected function before()
    {
        if(empty($this->template)){
            $this->template = 'layout';
        }
    }

    protected function after()
    {

    }

    protected function doExecute()
    {
        $this->before();
        $action = $this->router->getAction();
        if(!method_exists($this, $action)){
            throw new \Exception('Action not found');
        }
        $this->{$action}();
        $this->viewHelper->render($this->template, array('content'=>$this->content), true);
        $this->after();
    }

    protected function setContent(string $view){
        $this->content = $view;
    }

}