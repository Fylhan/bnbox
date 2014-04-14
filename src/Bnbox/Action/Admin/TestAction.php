<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');
require_once(INCLUDE_PATH . '/lib/security/Bcrypt.class.php');

class TestAction extends Action {
	public function launch($params = array()) {
		$params = array();
		$this->render($params);
	}

	public function render($params = array()) {
		// -- Prepare Meta Data
		$this->_controller->getResponse()->addVar('metaTitle', 'Administration - Test');

		// -- Create params
		$tplPparams = array('UrlCourant' => 'administration-test.html');
		$tplPparams = array_merge($tplPparams, $params);
		if (DEBUG && !empty($_GET['bcrypt'])) {
			$passwordChecker = new Bcrypt();
			$tplPparams['HashedWord'] = $passwordChecker->hash(parserS($_GET['bcrypt']));
		}

		// -- Fill the body and print the page
		$this->_controller->render('administration/layout-test.tpl', $tplPparams);
		$this->printOut();
	}
}
?>
