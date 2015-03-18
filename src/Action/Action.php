<?php
namespace Bnoger\Action;

abstract class Action
{

    /**
     * 
     * @var \Bnoger\Controller\MainController
     */
    protected $controller;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    abstract public function launch($params = NULL);

    public function render($toDisplay)
    {
        $this->controller->render($toDisplay);
    }

    public function printOut()
    {
        $this->controller->getResponse()->printOut();
    }

    protected function _forward($bundle, $module, $action, $params = null)
    {
        $this->controller->forward($bundle, $module, $action, $params);
    }

    protected function _redirect($url, $permanent = false)
    {
        $this->controller->redirect($url, $permanent);
    }
}
