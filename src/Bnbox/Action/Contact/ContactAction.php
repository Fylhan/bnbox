<?php
namespace Bnbox\Action\Contact;

use Bnoger\Action\Action;

class ContactAction extends Action
{

    public function launch($params = array())
    {
        $this->render();
    }

    public function render($params = array())
    {
        $this->controller->getResponse()->addPage('title', 'Contact');
        $this->controller->render('contact/contact.html.twig', $params);
        $this->printOut();
    }
}
?>
