<?php
namespace Bnoger\Action;

abstract class Action
{

    protected $_controller;

    public function __construct($controller)
    {
        $this->_controller = $controller;
    }

    abstract public function launch($params = NULL);

    public function render($toDisplay)
    {
        $this->_controller->render($toDisplay);
    }

    public function printOut()
    {
        $this->_controller->getResponse()->printOut();
    }

    protected function _forward($module, $action, $params = null)
    {
        $this->_controller->forward($module, $action, $params);
    }

    protected function _redirect($url, $permanent = false)
    {
        $this->_controller->redirect($url, $permanent);
    }
}
