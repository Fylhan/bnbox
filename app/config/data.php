<?php
date_default_timezone_set('Europe/Paris');

// Nom du site
define('SiteCode', 'bnbox');
define('SiteName', 'La Bnbox !');
define('SiteDesc', 'Créateur de sourires.');

define('LastModification', mktime(0, 0, 0, 03, 21, 2013));
if (!defined('LastModificationActualites')) {
	define('LastModificationActualites', LastModification);
}
define('ParameterFilePath', CACHE_PATH . '/data-user.php');
define('BanFilePath', CACHE_PATH . '/ban.json');
define('GaleryFilePath', CACHE_PATH . '/galery.json');
define('UploadDir', DATA_PATH . '/upload/');

// Constantes meta
if (!defined('Encodage')) {
	define('Encodage', 'UTF-8');
}
if (!defined('DefaultLocale')) {
	define('DefaultLocale', 'fr_FR');
}
define('Author', 'Vincent et Olivier');
define('MetaTitleDefault', SiteName . ' - ' . SiteDesc);
define('MetaKwDefault', 'Dieu, église, protestant, évangélique, cep, saint-maur');
define('MetaDescDefault', SiteDesc . ' de Saint-Maur');
define('MetaTitle', SiteName);
define('MetaKw', 'Dieu, église, cep, saint-maur');
define('MetaDesc', SiteName);

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
if (DEBUG) {
	define('EmailFlambeaux', EmailAdmin);
	define('EmailGDJ', EmailAdmin);
	define('EmailContact', EmailAdmin);
} else {
	define('EmailFlambeaux', 'flambeaux@cepsaintmaur.fr');
	define('EmailGDJ', 'gdj@cepsaintmaur.fr');
	define('EmailContact', 'contact@cepsaintmaur.fr');
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
	define('CompressionEnabled', true);
}
define('StyleEnabled', true);
$t = 1; // Tabindex

define('OK_REDIRECTION', 1);
define('OK_NONBLOQUANT', 2);
define('OK_BLOQUANT', 3);
define('ERREUR_BLOQUANT', 0);
define('ERREUR_NONBLOQUANT', -1);
define('ERREUR_REDIRECTION', -2);
define('ERREUR', 0);
define('OK', 1);
define('NEUTRE', 2);
