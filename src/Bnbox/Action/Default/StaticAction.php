<?php
require_once(INCLUDE_PATH.'/action/Action.abstract.php');

class StaticAction extends Action {
	public function launch($params=array()) {
		$this->render($params);
	}
	
	public function render($params=array()) {
		// -- Prepare Meta Data
		$page = $this->_controller->getRequest()->getAction();
		if ('activites' == $page) {
			$this->_controller->getResponse()->addVar('metaTitle', 'Nos activités');
			$this->_controller->getResponse()->addVar('metaDesc', 'Au CEP Saint-Maur, nous proposons régulièrement diverses activités pour les plus jeunes comme pour les plus âgés.');
			$this->_controller->getResponse()->addVar('metaKw', 'culte, atelier, flambeaux, scout, scoutisme, gdj, groupe de jeunes');
		}
		elseif ('qui-sommes-nous' == $page) {
			$this->_controller->getResponse()->addVar('metaTitle', 'Nous connaître');
			$this->_controller->getResponse()->addVar('metaDesc', 'Le CEP Saint-Maur est une communauté évangélique protestante. Quelle est notre histoire ? Qui sommes-nous ? En quoi croyons-nous ?');
			$this->_controller->getResponse()->addVar('metaKw', 'jésus, saint-esprit, protestant, évangélique, caef, cnef, confession de foi');
		}
		elseif ('politique-accessibilite' == $page) {
			$this->_controller->getResponse()->addVar('metaTitle', 'Politique d\'accessibilité');
			$this->_controller->getResponse()->addVar('metaDesc', 'Politique d\'accessibilité du site du CEP Saint-Maur.');
			$this->_controller->getResponse()->addVar('metaKw', 'accesskey, accessibilité, about');
		}
		// 404
		else {
			$e = new MVCException();
			$e->setPage('" '.$page.' "');
			throw $e;
		}
		
		// -- Create params
		$tplPparams = array();
		
		// -- Fill the body and print the page
		$this->_controller->render('default/layout-'.$page.'.tpl', $tplPparams);
		$this->printOut();
	}
}
?>