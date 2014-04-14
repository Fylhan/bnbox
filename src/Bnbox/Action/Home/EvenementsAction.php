<?php
require_once(INCLUDE_PATH.'/action/Action.abstract.php');
require_once(INCLUDE_PATH.'/exception/Message.class.php');
require_once(INCLUDE_PATH.'/data/accessor/ActualiteDAOImpl.class.php');

class AccueilAction extends Action {
	public function launch($params=NULL) {
		// -- Fill the View
		$this->render($params);
	}
	
	public function render($params=NULL) {
		// -- Bloc Actualites
		// Récupération des éléments
		$actualiteModel = new ActualiteDAOImpl();
		$page = calculPage();
		$this->_controller->getResponse()->addVar('page', $page);
		$nbElement = $actualiteModel->calculNbActualites();
		$nbPage = calculNbPage(NbParPage, $nbElement);
		$appellationElement = 'actualité';
		$actualites = $actualiteModel->findActualites($page);
		if (NULL != $actualites && count($actualites) > 0) {
			foreach($actualites AS $actualite) {
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
			'UrlTri' => '#actualites',
		);
		
		// -- Fill the body and print the page
		$this->_controller->render('accueil/actualites.tpl', $tplPparams);
		$this->printOut();
	}
}
?>
