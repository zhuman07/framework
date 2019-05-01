<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 17:31
 */
namespace Core\Classes\Commands;

use Core\Classes\Command;
use Core\Classes\Mapper;
use Core\Classes\Router;
use Core\Classes\ServiceContainer;
use Core\Classes\ViewHelper;

class ControllerCommand extends Command
{

    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @var string
     */
    protected $template;

    protected $content;

    /**
     * @var bool
     */
    protected $render = true;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ViewHelper
     */
    protected $viewHelper;

    /**
     * @var Mapper
     */
    protected $mapper;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->router = $serviceContainer->get('router');
        $this->viewHelper = $serviceContainer->get('viewHelper');
        $this->mapper = $serviceContainer->get('mapper');
    }

    protected function before(): void
    {
        if(empty($this->template)){
            $this->template = 'layout';
        }
    }

    protected function after(): void
    {

    }

    protected function doExecute()
    {
        $this->before();
        $action = $this->router->getAction();
        if(!method_exists($this, $action)){
            throw new \Exception("Action {$action} not found");
        }
        $this->{$action}();
        if($this->render === true){
            $this->viewHelper->render($this->template, array('content'=>$this->content), true);
        }
        $this->after();
    }

    protected function setContent(string $view){
        $this->content = $view;
    }

}