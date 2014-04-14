<?php
namespace Bnoger\Action\Filter;

use Bnoger\Entity\Exception\Message;

class MessageFilter implements Filterable
{

    protected $_controler;

    public function __construct($controler)
    {
        $this->_controler = $controler;
    }

    public function preFilter()
    {
        if (isset($_GET['data']) && NULL != $_GET['data']) {
            $data = htmlspecialchars(base64_decode($_GET['data']));
            $type = intval($_GET['type']);
            $message = new Message($data, $type);
            $this->_controler->getResponse()->addBlock('Message', $message->toString());
        }
        return true;
    }

    public function postFilter()
    {}
}