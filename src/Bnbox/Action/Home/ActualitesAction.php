<?php
require_once(INCLUDE_PATH.'/action/Action.abstract.php');
require_once(INCLUDE_PATH.'/action/accueil/AccueilAction.class.php');

class ActualitesAction extends Action {
	public function launch($params=NULL) {
		// -- Fill the View
		$this->render($params);
	}
	
	public function render($params=NULL) {
		// -- Create params
		$accueilAction = new AccueilAction($this->_controller);
		$tplPparams = $accueilAction->generateActualites();
		$tplPparams['UrlTri'] = '';
		$tplPparams['UrlCourant'] = 'evenements.html';
		$this->_controller->getResponse()->addVar('metaTitle', 'Dernières nouvelles');
		$this->_controller->getResponse()->addVar('metaDesc', 'Dernières nouvelles');
		$this->_controller->getResponse()->addVar('metaKw', 'news, événements');
		
		// -- Fill the body and print the page
		$this->_controller->render('accueil/layout-actualites.tpl', $tplPparams);
		$this->printOut();
	}
}
?>
