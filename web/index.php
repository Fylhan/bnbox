<?php
require __DIR__.'/../src/check_setup.php';
require __DIR__.'/../src/common.php';
// Session
ini_set('session.use_cookies', 1);       // Use cookies to store session.
ini_set('session.use_only_cookies', 1);  // Force cookies for session (phpsessionID forbidden in URL)
ini_set('session.use_trans_sid', false); // Prevent php to use sessionID in URL if cookies are disabled.
session_name(SITE_CODE);
session_start();

// Compression
if (CompressionEnabled) {
    ob_start('ob_gzhandler');
}

use Core\Router;

$router = new Router($container);
$router->execute();
