<?php

// Enable/disable debug
defined('ETAT') or define('ETAT', 'production'); // developpement, production, maintenance
defined('HEBERGEMENT') or define('HEBERGEMENT', 'en ligne'); // local, en ligne
defined('DEBUG') or define('DEBUG', false);
defined('DEBUG_FILE') or define('DEBUG_FILE', __DIR__.'/../app/cache/debug.log');

if (HEBERGEMENT == 'local') {
    define('SITE_PATH', 'http://bnbox.local/');
}
else {
    define('SITE_PATH', 'http://la-bnbox.fr/test/');
}

// Application version
defined('APP_VERSION') or define('APP_VERSION', 'master');

// Base directory
define('BASE_URL_DIRECTORY', dirname($_SERVER['PHP_SELF']));

// Path
defined('POSITION_RELATIVE') or define('POSITION_RELATIVE', '');
define('APP_PATH', __DIR__ .'/../app');
define('DATA_PATH', APP_PATH);
define('SRC_PATH', __DIR__ .'/../src');
define('INCLUDE_PATH', SRC_PATH);
define('TPL_PATH', SRC_PATH.'/Template');
define('ENTITY_PATH', SRC_PATH . '/Entity');
define('VENDOR_PATH', __DIR__ .'/../vendor');
define('WEB_PATH4SRC', __DIR__ .'/../web');
define('WEB_PATH', '');
define('JS_PATH', WEB_PATH.'/js');
define('CSS_PATH', WEB_PATH.'/css');
define('FONT_PATH', WEB_PATH.'/font');
define('IMG_PATH', WEB_PATH.'/img');

define('CACHE_PATH', APP_PATH.'/cache');
define('CONFIG_PATH', APP_PATH.'/config');
define('DOCUMENT_PATH', APP_PATH . '/document');
define('ILLUSTRATION_PATH', APP_PATH . '/illustration');
define('EMAIL_PATH', CACHE_PATH . '/email');
define('BNCODE_PATH', SRC_PATH . '/Service/bncode');
define('GESHI_PATH', BNCODE_PATH . '/geshi');
define('FEED_PATH', SITE_PATH . 'feed.xml');

// Database driver: sqlite, mysql or postgres
defined('DB_DRIVER') or define('DB_DRIVER', 'sqlite');

// Sqlite configuration
defined('DB_FILENAME') or define('DB_FILENAME', '../data/db.sqlite');

// Mysql/Postgres configuration
defined('DB_USERNAME') or define('DB_USERNAME', 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', '');
defined('DB_HOSTNAME') or define('DB_HOSTNAME', 'localhost');
defined('DB_NAME') or define('DB_NAME', 'kanboard');
defined('DB_PORT') or define('DB_PORT', null);

// LDAP configuration
defined('LDAP_AUTH') or define('LDAP_AUTH', false);
defined('LDAP_SERVER') or define('LDAP_SERVER', '');
defined('LDAP_PORT') or define('LDAP_PORT', 389);
defined('LDAP_START_TLS') or define('LDAP_START_TLS', false);
defined('LDAP_SSL_VERIFY') or define('LDAP_SSL_VERIFY', true);
defined('LDAP_BIND_TYPE') or define('LDAP_BIND_TYPE', 'anonymous');
defined('LDAP_USERNAME') or define('LDAP_USERNAME', null);
defined('LDAP_PASSWORD') or define('LDAP_PASSWORD', null);
defined('LDAP_ACCOUNT_BASE') or define('LDAP_ACCOUNT_BASE', '');
defined('LDAP_USER_PATTERN') or define('LDAP_USER_PATTERN', '');
defined('LDAP_ACCOUNT_FULLNAME') or define('LDAP_ACCOUNT_FULLNAME', 'displayname');
defined('LDAP_ACCOUNT_EMAIL') or define('LDAP_ACCOUNT_EMAIL', 'mail');
defined('LDAP_ACCOUNT_ID') or define('LDAP_ACCOUNT_ID', '');
defined('LDAP_USERNAME_CASE_SENSITIVE') or define('LDAP_USERNAME_CASE_SENSITIVE', false);

// Google authentication
defined('GOOGLE_AUTH') or define('GOOGLE_AUTH', false);
defined('GOOGLE_CLIENT_ID') or define('GOOGLE_CLIENT_ID', '');
defined('GOOGLE_CLIENT_SECRET') or define('GOOGLE_CLIENT_SECRET', '');

// GitHub authentication
defined('GITHUB_AUTH') or define('GITHUB_AUTH', false);
defined('GITHUB_CLIENT_ID') or define('GITHUB_CLIENT_ID', '');
defined('GITHUB_CLIENT_SECRET') or define('GITHUB_CLIENT_SECRET', '');

// Proxy authentication
defined('REVERSE_PROXY_AUTH') or define('REVERSE_PROXY_AUTH', false);
defined('REVERSE_PROXY_USER_HEADER') or define('REVERSE_PROXY_USER_HEADER', 'REMOTE_USER');
defined('REVERSE_PROXY_DEFAULT_ADMIN') or define('REVERSE_PROXY_DEFAULT_ADMIN', '');
defined('REVERSE_PROXY_DEFAULT_DOMAIN') or define('REVERSE_PROXY_DEFAULT_DOMAIN', '');

// Mail configuration
defined('MAIL_FROM') or define('MAIL_FROM', 'notifications@kanboard.local');
defined('MAIL_TRANSPORT') or define('MAIL_TRANSPORT', 'mail');
defined('MAIL_SMTP_HOSTNAME') or define('MAIL_SMTP_HOSTNAME', '');
defined('MAIL_SMTP_PORT') or define('MAIL_SMTP_PORT', 25);
defined('MAIL_SMTP_USERNAME') or define('MAIL_SMTP_USERNAME', '');
defined('MAIL_SMTP_PASSWORD') or define('MAIL_SMTP_PASSWORD', '');
defined('MAIL_SMTP_ENCRYPTION') or define('MAIL_SMTP_ENCRYPTION', null);
defined('MAIL_SENDMAIL_COMMAND') or define('MAIL_SENDMAIL_COMMAND', '/usr/sbin/sendmail -bs');

// Enable or disable "Strict-Transport-Security" HTTP header
defined('ENABLE_HSTS') or define('ENABLE_HSTS', true);

// Default files directory
defined('FILES_DIR') or define('FILES_DIR', 'data/files/');

defined('SecurityKey') or define('SecurityKey', 'PUTYOURSECURITYKEYHERE'); // bcrypt

date_default_timezone_set('Europe/Paris');

define('LOCALE', 'fr_FR');
define('CHARSET', 'UTF-8');
define('SITE_CODE', 'bnbox');
define('SITE_NAME', 'Bnbox');

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
    define('EmailAdmin', 'fgfdsg@fgddsggfsd.fr');
}
if (!defined('EmailContact')) {
    define('EmailContact', 'fgfdsg@fgddsggfsd.fr');
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
