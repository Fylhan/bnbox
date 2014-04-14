<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');

class GaleryAction extends Action {
	public function launch($params = array()) {
			$this->render($params);
	}

	public function render($params = array()) {
		// -- Header
		$this->_controller->getResponse()->addHeader('Cache-Control', 'no-cache, must-revalidate');
		$this->_controller->getResponse()->addHeader('Expires', 'Sat, 29 Oct 2011 13:00:00 GMT+1');// A date in the past
		$this->_controller->getResponse()->addHeader('Content-type', 'application/json; charset=UTF-8');
		// -- Display content
		$data = file_get_contents(GaleryFilePath);
		$this->_controller->getResponse()->setBody(false != $data ? $data : '');
		$this->printOut();
	}
}
?>
