<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');

class ListesDiffusionAction extends Action {
	public function launch($params = array()) {
		$params = array();
		$params['ListesDiffusion'] = array('flambeaux@cepsaintmaur.com', 'gdj@cepsaintmaur.com', 'musique@cepsaintmaur.com');		
		$this->render($params);
	}

	public function render($params = array()) {
		// -- Prepare Meta Data
		$this->_controller->getResponse()->addVar('metaTitle', 'Administration - Gérer les listes de diffusion');

		// -- Create params
		$tplPparams = array('UrlCourant' => 'administration-listes-diffusion.html');
		$tplPparams = array_merge($tplPparams, $params);

		// -- Fill the body and print the page
		$this->_controller->render('administration/layout-diffusion-list.tpl', $tplPparams);
		$this->printOut();
	}
}
?>
