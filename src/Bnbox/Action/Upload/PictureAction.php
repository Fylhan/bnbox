<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');
require_once(INCLUDE_PATH . '/service/FileUploader.php');

class PictureAction extends Action {
	public function launch($params = array()) {
		if (isset($_FILES['file']) && NULL != $_FILES['file'] && '' != $_FILES['file']) {
			// -- Load fileUploader
			$acceptedMimeTypes = array('image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg');
			$acceptedExtensionTypes = array('png', 'jpg', 'gif', 'jpeg', 'pjpeg');
			$acceptedTypes = array(
							'png' => 'image/png',
							'jpe' => 'image/jpeg',
							'jpeg' => 'image/jpeg',
							'pjpeg' => 'image/pjpeg',
							'jpg' => 'image/jpg',
							'gif' => 'image/gif',
							'ico' => 'image/vnd.microsoft.icon',
							'svg' => 'image/svg+xml',
							'svgz' => 'image/svg+xml',
					);
			$fileUpload = new FileUploader(UploadDir, $acceptedMimeTypes, $acceptedExtensionTypes);

			// -- Upload
			$fileInfo = $fileUpload->upload($_FILES['file']);
			if (NULL != $fileInfo && $fileInfo['ack']) {
				// Manage gallery
				$fileUpload->addToGallery($fileInfo);
			}
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
