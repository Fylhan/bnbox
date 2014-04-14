<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');
require_once(INCLUDE_PATH . '/service/FileUploader.php');

class FileAction extends Action {
	public function launch($params = array()) {
		if (isset($_FILES['file']) && NULL != $_FILES['file'] && '' != $_FILES['file']) {
			// -- Load fileUploader
			$acceptedTypes = array(
					'txt' => 'text/plain',
					'zip' => 'application/zip',
					'mp3' => 'audio/mpeg',
					'qt' => 'video/quicktime',
					'mov' => 'video/quicktime',
					'pdf' => 'application/pdf',
					'doc' => 'application/msword',
					'rtf' => 'application/rtf',
					'xls' => 'application/vnd.ms-excel',
					'ppt' => 'application/vnd.ms-powerpoint',
					'odt' => 'application/vnd.oasis.opendocument.text',
					'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);
			$fileUpload = new FileUploader(UploadDir, $acceptedTypes);

			// -- Upload
			$fileInfo = $fileUpload->upload($_FILES['file']);
			$this->render($fileInfo);
		}

	}

	public function render($params = array()) {
		// -- Fill the body and print the page
		$toDisplay = $params;
		if (NULL != $params && $params['ack']) {
			$toDisplay = array('filelink' => $params['dir'] . $params['name'], 'title' => $params['title']);
		}
		$this->_controller->getResponse()->setBody(stripslashes(json_encode($toDisplay)));
		$this->printOut();
	}
}
?>
