<?php
date_default_timezone_set('Europe/Paris');

define('LOCALE', 'fr_FR');
define('CHARSET', 'UTF-8');
define('SITE_CODE', 'bnbox');
define('SITE_NAME', 'La Bnbox !');

$app = array(
    'locale' => LOCALE,
    'charset' => CHARSET,
    'name' => SITE_NAME,
    'url' => SITE_PATH,
    'title' => SITE_NAME,
    'description' => 'Créateur de sourires',
    'authors' => 'Fylhan',
    'keywords' => 'bnbox,bn,cours,entraide,scolaire,collège,lycée,résumé,corrigé',
);


// Autres variables
define('HAAT', 'haat');
define('DOHOT', 'dohot');
if (!defined('NbParPage')) {
	define('NbParPage', '2');
}
if (!defined('NbParPageAdmin')) {
	define('NbParPageAdmin', '20');
}
if (!defined('NbMaxLienPagination')) {
	define('NbMaxLienPagination', '10');
}
if (!defined('NbItemPerFeed')) {
	define('NbItemPerFeed', '20');
}
if (!defined('EmailAdmin')) {
	define('EmailAdmin', 'olivier@maridat.com');
}
if (!defined('EmailContact')) {
	define('EmailContact', 'olivier@maridat.com');
}
if (!defined('CodeStats')) {
	define('CodeStats', '');
}
if (!defined('CodeWebmasterTools')) {
	define('CodeWebmasterTools', '');
}
if (!defined('Antibot')) {
	define('Antibot', 12);
}
if (!defined('DisplayHelp')) {
	define('DisplayHelp', 1);
}
if (!defined('SessionTimeoutKeepConnected')) {
	define('SessionTimeoutKeepConnected', 60*60*24*365); // 1 year
}
if (!defined('SessionTimeoutNormal')) {
	define('SessionTimeoutNormal', 60*60*2); // 2 hours
}
if (!defined('LoginTryNumber')) {
	define('LoginTryNumber', 5); // 2 hours
}
if (!defined('LoginBanishedTimeout')) {
	define('LoginBanishedTimeout', 30*60); // 30 min
}
if (!defined('CacheEnabled')) {
	define('CacheEnabled', !DEBUG);
}
if (!defined('CompressionEnabled')) {
	define('CompressionEnabled', false);
}
if (!defined('StyleEnabled')) {
	define('StyleEnabled', true);
}
$t = 1; // Tabindex

