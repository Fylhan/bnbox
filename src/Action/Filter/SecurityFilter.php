<?php
namespace Bnoger\Action\Filter;

use Bnoger\Service\Security\LoginManager;

class SecurityFilter implements Filterable
{

    protected $_controller;

    private $loginManager;

    public function __construct($controller)
    {
        $this->_controller = $controller;
        $this->loginManager = new LoginManager(SecurityKey, $this->_controller);
    }

    public function preFilter()
    {
        $protectedModules = array(
            'administration',
            'upload'
        );
        if (! in_array($this->_controller->getRequest()->getModule(), $protectedModules)) {
            // Continue after prefilter
            return true;
        }
        
        /*
         * Algorithm * If est déjà loggé (session uid existe, pad de changement d'IP et pas la fin) : return true * If se logue (not banished, security key, bcrypt, enregistrement des sessions, avec des cookies pour conserver la session malgrè la fermeture du navigateur) : manage banishement and return true * Sinon (error ou arrivée sur la page sans être logué, affichage formulaire de login): return false
         */
        // -- Is already logged in
        if ($this->loginManager->isLoggedIn()) {
            // Continue after prefilter
            return true;
        }
        
        // -- Login request: check authentication
        if ($this->loginManager->login()) {
            // Continue after prefilter
            return true;
        }
        
        // -- Error, or no security key: display only the form
        // - Prepare Meta Data
        $this->_controller->getResponse()->addVar('metaTitle', 'Administration - Authentification');
        
        // - Create params
        $tplParams = array(
            'UrlCourant' => 'administration.html'
        );
        
        // - Fill the body and print the page
        $this->_controller->render('administration/layout-security-form.tpl', $tplParams);
        $this->_controller->getResponse()->printOut();
        
        // -- Stop after prefilter
        return false;
    }

    public function postFilter()
    {}
}
