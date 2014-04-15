<?php
require_once(__DIR__ . '/../app/config/config.php');
require_once(VENDOR_PATH.'/autoload.php');
require_once(CACHE_PATH.'/data-user.php');
require_once(CONFIG_PATH.'/data.php');
require_once(SRC_PATH.'/Bnoger/Utils/tools.php');
ini_set('session.use_cookies', 1);       // Use cookies to store session.
ini_set('session.use_only_cookies', 1);  // Force cookies for session (phpsessionID forbidden in URL)
ini_set('session.use_trans_sid', false); // Prevent php to use sessionID in URL if cookies are disabled.
session_name(SITE_CODE);
session_start();

// --- Compression
if (CompressionEnabled) {
	ob_start('ob_gzhandler');
}

// --- Traitement
use Bnoger\Controller\MainController;

$mainControler = MainController::getInstance()->dispatch();
