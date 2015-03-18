<?php

class FileUploader {
	private $phpOffset;
	private $realOffset;
	private $dir;
	private $acceptedTypes;

	public function __construct($uploadDir, $acceptedTypes) {
		$this->phpOffset = './';
		$this->realOffset = './';
		$this->dir = $uploadDir;
		$this->acceptedTypes = $acceptedTypes;
// 		$acceptedTypes = array(
// 				'txt' => 'text/plain',
// 				'htm' => 'text/html',
// 				'html' => 'text/html',
// 				'php' => 'text/html',
// 				'css' => 'text/css',
// 				'js' => 'application/javascript',
// 				'json' => 'application/json',
// 				'xml' => 'application/xml',
// 				'swf' => 'application/x-shockwave-flash',
// 				'flv' => 'video/x-flv',
					
// 				// images
// 				'png' => 'image/png',
// 				'jpe' => 'image/jpeg',
// 				'jpeg' => 'image/jpeg',
// 				'jpg' => 'image/jpeg',
// 				'gif' => 'image/gif',
// 				'bmp' => 'image/bmp',
// 				'ico' => 'image/vnd.microsoft.icon',
// 				'tiff' => 'image/tiff',
// 				'tif' => 'image/tiff',
// 				'svg' => 'image/svg+xml',
// 				'svgz' => 'image/svg+xml',
					
// 				// archives
// 				'zip' => 'application/zip',
// 				'rar' => 'application/x-rar-compressed',
// 				'exe' => 'application/x-msdownload',
// 				'msi' => 'application/x-msdownload',
// 				'cab' => 'application/vnd.ms-cab-compressed',
					
// 				// audio/video
// 				'mp3' => 'audio/mpeg',
// 				'qt' => 'video/quicktime',
// 				'mov' => 'video/quicktime',
					
// 				// adobe
// 				'pdf' => 'application/pdf',
// 				'psd' => 'image/vnd.adobe.photoshop',
// 				'ai' => 'application/postscript',
// 				'eps' => 'application/postscript',
// 				'ps' => 'application/postscript',
					
// 				// ms office
// 				'doc' => 'application/msword',
// 				'rtf' => 'application/rtf',
// 				'xls' => 'application/vnd.ms-excel',
// 				'ppt' => 'application/vnd.ms-powerpoint',
					
// 				// open office
// 				'odt' => 'application/vnd.oasis.opendocument.text',
// 				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
// 		);
	}

	public function upload($file) {
		// -- Verifications
		if (0 != $file["error"]) {
			return array('ack' => false, 'ackMessage' => $this->displayError($file["error"]));
		}
		$fileMimeType = strtolower($file['type']);
		$fileExtensionType = strtolower(substr(strrchr($file['name'], '.'), 1));
		$fileExtensionTypePosition = strrpos($file['name'], '.');
		if (!$this->validate($fileMimeType, $fileExtensionType)) {
			return array('ack' => false, 'ackMessage' => 'Bad extension. Accepted: '.implode(', ', array_keys($this->acceptedTypes)));
		}
		list($fileName, $fileTitle) = $this->rename($file['name'], $fileMimeType, $fileExtensionType, $fileExtensionTypePosition);
		$filePath = $this->phpOffset . $this->dir . $fileName;

		// -- Upload
		$uploaded = move_uploaded_file($file['tmp_name'], $filePath);
		// Error
		if (!$uploaded) {
			return array('ack' => false, 'ackMessage' => 'error during upload');
		}

		// -- Display result
		return array('ack' => true, 'dir' => $this->dir, 'name' => $fileName, 'title' => $fileTitle);
	}

	public function addToGallery($fileInfo) {
		// Add to gallery
		$content = file_get_contents(GaleryFilePath);
		$gallery = array();
		if (null != $content && '' != $content) {
			$gallery = json_decode($content);
		}
		$gallery[] = array('thumb'=> $this->realOffset . $fileInfo['dir'] . $fileInfo['name'], 'image' => $this->realOffset . $fileInfo['dir'] . $fileInfo['name'], 'title' => $fileInfo['title']);
		file_put_contents(GaleryFilePath, preg_replace('!},\s*!', '},' . "\n", json_encode($gallery)));
	}

	private function validate($fileMimeType, $fileExtensionType) {
		// - Accepted extension
		if (!in_array($fileMimeType, array_values($this->acceptedTypes)) && !in_array($fileExtensionType, array_keys($this->acceptedTypes))) {
			return false;
		}
		return true;
	}

	private function rename($currentFileName, $fileMimeType, $fileExtensionType, $fileExtensionTypePosition) {
		// - Change filename
		$cleanFileName = parserUrl(substr($currentFileName, 0, $fileExtensionTypePosition));
		$fileTitle = deparserUrl($cleanFileName);
		$fileName = $cleanFileName . '_' . md5(date('YmdHis')) . '.'.$fileExtensionType;
		return array($fileName, $fileTitle);
	}
	
	private function displayError($errorCode) {
		switch($errorCode) {
			case UPLOAD_ERR_OK: return "Ok";
			case UPLOAD_ERR_INI_SIZE: return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
			case UPLOAD_ERR_FORM_SIZE: return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
			case UPLOAD_ERR_PARTIAL: return "The uploaded file was only partially uploaded.";
			case UPLOAD_ERR_NO_FILE: return "No file was uploaded.";
			case UPLOAD_ERR_NO_TMP_DIR: return "Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.";
			case UPLOAD_ERR_CANT_WRITE: return "Failed to write file to disk. Introduced in PHP 5.1.0.";
			case UPLOAD_ERR_EXTENSION: return "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.";
		}
	}
}
