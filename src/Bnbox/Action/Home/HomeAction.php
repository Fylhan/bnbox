<?php
namespace Bnbox\Action\Home;

use Bnoger\Action\Action;
use Bnoger\Entity\Exception\Message;
use Bnbox\Entity\Accessor\ActualiteDAOImpl;

class HomeAction extends Action
{

    public function launch($params = NULL)
    {
        // -- Fill the View
        $this->render($this->generateActualites());
    }

    public function render($params = NULL)
    {
        // -- Fill the body and print the page
        $this->_controller->render('accueil/layout-accueil.tpl', $params);
        $this->printOut();
    }

    public function generateActualites()
    {
        // -- Bloc Actualites
        // Récupération des éléments
        $actualiteModel = new ActualiteDAOImpl();
        $page = calculPage();
        $this->_controller->getResponse()->addVar('page', $page);
        $nbElement = $actualiteModel->calculNbActualites();
        $nbPage = calculNbPage(NbParPage, $nbElement);
        $appellationElement = 'événement';
        $actualites = $actualiteModel->findActualites($page);
        if (NULL != $actualites && count($actualites) > 0) {
            foreach ($actualites as $actualite) {
                $actualite->computeExtrait();
            }
        }
        
        // -- Create params
        $tplPparams = array(
            'actualites' => $actualites,
            'nbPage' => $nbPage,
            'nbMaxLienPagination' => NbMaxLienPagination,
            'page' => $page,
            'nbElement' => $nbElement,
            'appellationElement' => $appellationElement,
            'UrlTri' => '#actualites'
        );
        return $tplPparams;
    }
}
