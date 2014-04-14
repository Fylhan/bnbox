<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');
require_once(INCLUDE_PATH . '/exception/Message.class.php');
require_once(INCLUDE_PATH . '/data/accessor/ActualiteDAOImpl.class.php');

class ActualiteAction extends Action {
	public function launch($params = NULL) {
		// -- Fill the View
		$this->render($params);
	}

	public function render($params = NULL) {
		// -- Bloc Actualites
		// Récupération des éléments
		$actualiteModel = new ActualiteDAOImpl();
		$id = parserI($this->_controller->getRequest()->getParam('id'));
		$actualite = $actualiteModel->findActualiteById($id, false);

		if (NULL != $actualite) {
			// -- Create params
			$actualite->computeExtrait();
			$tplPparams = array('actualite' => $actualite,);

			// -- Prepare Meta Data
			$this->_controller->getResponse()->addVar('metaTitle', $actualite->getTitre());
			$this->_controller->getResponse()->addVar('metaDesc', 'Dernières nouvelles : ' + $actualite->getTitre());
			$this->_controller->getResponse()->addVar('metaKw', 'news');
		}
		// 404
		else {
			$e = new MVCException();
			$e->setPage('d\'actualité '.$id);
			throw $e;
		}
		// -- Fill the body and print the page
		$this->_controller->render('accueil/layout-actualite.tpl', $tplPparams);
		$this->printOut();
	}
}
?>
