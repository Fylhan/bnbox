<?php
namespace Bnbox\Action\Home;

use Bnoger\Action\Action;

class HomeAction extends Action
{

    public function launch($params = array())
    {
        $this->render();
    }

    public function render($params = array())
    {
        $this->controller->render('home/index.html.twig', $params);
        $this->printOut();
    }
}
