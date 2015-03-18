<?php

function parserI($int) {
	return intval(trim($int));
}
function parserF($decimal) {
	return floatval(trim($decimal));
}

function parserS($str) {
	$res = trim($str);
	if (!get_magic_quotes_gpc()) {
		$res = addslashes($res);
	}
	return $res;
}
function deparserS($str) {
	return stripslashes(trim($str));
}
function parserGeneric($str) {
	$res = trim($str);
	return $res;
}

/**
 * $level=0 > returns text only (no html or script)
 * 1 > text + html (no script)
 * 2 > all content secured with entities
 */
function secure($var, $level=0){
	if (is_array($var)) {
		foreach($var as $index=>$v) {
			$var[$index] = secure($v,$level);
		}
	}
	elseif (is_string($var)) {
		if ($level==0) {
			$var = strip_tags($var);
		}
		elseif ($level==1) {
			$var = preg_replace('#on[a-z]+ ?= ?["\'].*?["\'](?=[ />])|</?script>|javascript:#i','',$var);
		}
		else {
			$var = str_replace(array('&', '<', '>', '"'), array('&quot;', '&lt;', '&gt;', '&quot;'), $var);//htmlspecialchars
		}
	}
	return $var;
}

/**
 * Test si un email est valide
 * @param $email Email à tester
 * @return true si c'est un email
 * @return false sinon
 */
function isEmailValide($email) {
	return preg_match('![a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}!i', $email);
}
/**
 * Test si un tel est valide
 * @param $tel tel à tester
 * @return true si c'est un tel
 * @return false sinon
 */
function isTelValide($tel) {
	return preg_match('!^0[1-9]([-. ]?[0-9]{2}){4}$!i', $tel);
}

// Transforme le numéro du mois en son nom en français
function nomMois($numMois) {
	switch ($numMois) {
	case 1:
		return 'janvier';
		break;
	case 2:
		return 'février';
		break;
	case 3:
		return 'mars';
		break;
	case 4:
		return 'avril';
		break;
	case 5:
		return 'mai';
		break;
	case 6:
		return 'juin';
		break;
	case 7:
		return 'juillet';
		break;
	case 8:
		return 'août';
		break;
	case 9:
		return 'septembre';
		break;
	case 10:
		return 'octobre';
		break;
	case 11:
		return 'novembre';
		break;
	default:
		return 'décembre';
	}
}

function approximeMinute($minute) {
	if ($minute > 00 && $minute <= 05)
		$minute = 05;
	else if ($minute > 05 && $minute <= 10)
		$minute = 10;
	else if ($minute > 10 && $minute <= 15)
		$minute = 15;
	else if ($minute > 15 && $minute <= 20)
		$minute = 20;
	else if ($minute > 20 && $minute <= 25)
		$minute = 25;
	else if ($minute > 25 && $minute <= 30)
		$minute = 30;
	else if ($minute > 30 && $minute <= 35)
		$minute = 35;
	else if ($minute > 35 && $minute <= 40)
		$minute = 40;
	else if ($minute > 40 && $minute <= 45)
		$minute = 45;
	else if ($minute > 45 && $minute <= 50)
		$minute = 50;
	else
		$minute = 55;
	return $minute;
}

// Formate une date en fonction de la date actuelle
function dateFr($timestamp, $heure = true, $le = true) {
	$timestampCourant = time();
	if (date('Y', $timestamp) == date('Y', $timestampCourant)) {
		if ($heure && date('z', $timestamp) == date('z', $timestampCourant)) { // Le même jour
			$s_date = 'aujourd\'hui à ' . date('G\hi', $timestamp);
		}
		elseif (date('z', $timestamp) == date('z', $timestampCourant) - 1) { // La veille
			$s_date = 'hier' . ($heure ? ' à ' . date('G\hi', $timestamp) : '');
		}
		else { // La même année
			$s_date = ($le ? 'le ' : '') . (date('d', $timestamp) + 0) . ' ' . nomMois(date('n', $timestamp))
					. ($heure ? ' à ' . date('G\hi', $timestamp) : '');
		}
	}
	else { // Une année différente
		$s_date = ($le ? 'le ' : '') . (date('d', $timestamp) + 0) . ' ' . nomMois(date('n', $timestamp)) . date(' Y', $timestamp)
				. ($heure ? ' à ' . date('G\hi', $timestamp) : '');
	}
	return $s_date;
}

/**
 * Parse une date au format DATE_RFC822
 * Il existe date(DATE_RFC822, timestamp), mais le serveur Flamb'clair ne le connait pas
 * @param int $timestamp Timestamp à parser
 */
function dateToRFC822($timestamp) {
	return date('D\, d M Y H\:i\:s O', $timestamp);
}

/**
 * Formate la date d'un événement
 * @param $timestamp Timestamp
 * @return La date formatée
 */
function afficherDateEvenement($timestamp) {
	return 'le ' . (date('d', $timestamp) + 0) . ' ' . nomMois(date('n', $timestamp)) . ' à ' . date('G\hi', $timestamp);
}
function salut() {
	$heure = date('G\.i', time());
	if ($heure <= 7 || $heure >= 22) {
		return 'bonne nuit';
	}
	elseif ($heure <= 11.30) {
		return 'bonjour';
	}
	elseif ($heure <= 13.30) {
		return 'bon appetit';
	}
	elseif ($heure <= 18) {
		return 'bonne après-midi';
	}
	else {
		return 'bonne soirée';
	}
}

/**
 * Récupére le tri séléctionné
 * @return int Le tri séléctionné
 */
function getTri() {
	if (isset($_GET['tri']) && $_GET['tri'] != NULL) {
		$tri = parserUrl($_GET['tri']);
	}
	else if (isset($_POST['tri']) && $_POST['tri'] != NULL) {
		$tri = parserUrl($_POST['tri']);
	}
	else if (isset($_COOKIES['tri']) && $_COOKIES['tri'] != NULL) {
		$tri = parserUrl($_COOKIES['tri']);
	}
	else if (isset($_SESSION['tri']) && $_SESSION['tri'] != NULL) {
		$tri = parserUrl($_SESSION['tri']);
	}
	else {
		$tri = 'libellea';
	}
	return $tri;
}

function getRecherche() {
	if (isset($_POST['actionRecherche']) && $_POST['actionRecherche'] != NULL && isset($_POST['recherche']) && NULL != $_POST['recherche']) {
		$recherche = parserS($_POST['recherche']);
	}
	return NULL;
}
/**
 * Calcul le type de tri SQL é effectuer en fonction du tri séléction
 * @param string $tri Tri séléctionné
 * @return int Le tri é effectuer
 */
function calculSqlTri($tri) {
	return substr($tri, 0, -1) . ' ' . (substr($tri, -1) == 'a' ? 'ASC' : 'DESC');
}

/**
 * Calcul le type de tri pour l'url à afficher en fonction du tri séléctionné
 * @param string $tri Tri séléctionné
 * @return int Le tri é effectuer
 */
function calculUrlTri($tri, $default) {
	return $tri != $default ? '?tri=' . $tri : '';
}

/**
 * Calcul la page sur laquelle on se trouve en fonction de l'environnement
 * @return int La page actuelle
 */
function calculPage() {
	if (isset($_GET['page']) && $_GET['page'] != NULL) {
		if ($_GET['page'] > 1) {
			$page = parserI($_GET['page']);
		}
		else {
			$page = 1;
		}
	}
	else {
		$page = 1;
	}
	return $page;
}

/**
 * Calcul le nombre d'éléments total en fonction de l'environnement
 * @pre Une requéte avec SQL_FOUND_ROWS() a précédement été réalisée
 * @param int $nbParPage Nombre d'éléments par page
 * @return int Le nombre d'éléments
 */ 
function calculNbElmnt() {
	$qry = 'SELECT FOUND_ROWS() AS nb_elmnt';
	$res = query($qry);
	$nbElmnt = $res[0]['nb_elmnt'];
	return $nbElmnt;
}

/**
 * Calcul la nombre de pages totales en fonction de l'environnement
 * @pre Une requête avec SQL_FOUND_ROWS() a précédement été réalisée
 * @param int $nbParPage Nombre d'éléments par page
 * @return int Le nombre de pages
 */ 
function calculNbPage($nbParPage, $nbElmnt) {
	$nbPage = ceil($nbElmnt / $nbParPage);
	if ($nbPage == 0)
		$nbPage = 1;
	return $nbPage;
}

function echa($array) {
	echo '<pre>';
	var_dump($array);
	echo '</pre><br />';
}

/**
 * Clean une chaine de caractères pour l'url rewriting
 * @param string $url Chaine de caractères à cleaner
 * @param boolean $elag True pour élaguer les petits mots (la, les, ...); false sinon
 * @return string La chaine de caractère rewritée
 */
function parserUrl($url, $elag = true, $strtolower = true) {
	if ($strtolower) {
		$url = mb_strtolower($url);
	}

	// Elagage
	if ($elag) {
		$url = preg_replace('!(?:^|\s|[_-])(le|la|les|un|une|des|de|à|sa|son|ses|ces)(?:$|\s|[_-])!i', '-', $url);
	}

	// Clean accent
	$url = preg_replace('!([àâä])!i', 'a', $url);
	$url = preg_replace('!([éèêë])!i', 'e', $url);
	$url = preg_replace('!([îï])!i', 'i', $url);
	$url = preg_replace('!([ôö])!i', 'o', $url);
	$url = preg_replace('!([ùüû])!i', 'u', $url);
	$url = preg_replace('!ÿ!i', 'y', $url);
	$url = preg_replace('!ç!i', 'c', $url);

	// Clean caractère
	$url = preg_replace('![/@\'=_ -]!i', '-', $url);
	$url = preg_replace('![&~"#|`^()+{}[\]$£¤*µ%§\!:;\\\.,?°]!i', '', $url);

	// Elagage final
	$url = preg_replace('!-(d|l|m|qu|t)-!i', '-', $url);
	$url = preg_replace('!^(d|l|m|qu|t)-!i', '-', $url);
	$url = preg_replace('!-(d|l|m|qu|t)&!i', '-', $url);
	$url = preg_replace('!-{2,}!i', '-', $url);
	$url = preg_replace('!^-!i', '', $url);
	$url = preg_replace('!-$!i', '', $url);

	return $url;
}

function deparserUrl($url) {
	$url = ucwords($url);
	$url = str_replace('-', ' ', $url);
	return $url;
}

/**
 * Rendre un url joli (on enlève http tout ça et on remplace par www)
 */
function cleanerUrl($url) {
	return 'www.' . preg_replace('!(?:http://)?(?:www\.)?(.+)!i', '$1', $url);
}

// Met au pluriel un mot
function pluriel($a_i_nombreElements, $a_s_mot) {
	if ($a_i_nombreElements > 1) {
		$s_motAuPluriel = $a_i_nombreElements . " " . $a_s_mot . "s";
	}
	elseif ($a_i_nombreElements == '1') {
		$s_motAuPluriel = "Un " . $a_s_mot;
	}
	else {
		$s_motAuPluriel = 'Aucun ' . $a_s_mot;
	}
	return $s_motAuPluriel;
}
function ajoutS($nbElmnt, $str) {
	if ($nbElmnt > 1) {
		return $str . 's';
	}
	else {
		return $str;
	}
}

// Tells if a string start with a substring or not.
function startsWith($haystack,$needle,$case=true)
{
	if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
	return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}

// Tells if a string ends with a substring or not.
function endsWith($haystack,$needle,$case=true)
{
	if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
	return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
}

/**
 * Retourne l'url de l'image si une image existe dans ce dossier avec ce nom
 * @param string $dossierOuChercher)
 * @param string $nomImage (sans extension)
 * @return Url de l'image si elle existe, vide sinon
 */
function isImage($dossierOuChercher, $nomImage) {
	// AJout d'un slash à la fin si besoin
	if ($dossierOuChercher[(strlen($dossierOuChercher) - 1)] != '/') {
		$dossierOuChercher .= '/';
	}
	// Boucle sur les extensions d'images
	$extensions = array('.jpg', '.jpeg', '.gif', '.png', '.JPG', '.JPEG', '.GIF', '.PNG');
	foreach ($extensions AS $extension) {
		if (is_file($dossierOuChercher . $nomImage . $extension)) {
			return $nomImage . $extension;
		}
	}
	// Si on n'a rien trouvé, on renvoie vide
	return '';
}

/**
 * Tronque un texte pour qu'il fasse $longueur caractères et y ajoute "..." si besoins
 * @param string $txt Texte à tronquer
 * @param int $longueur La longueur du texte final
 * @return string Le texte tronqué
 */
function getExtrait($txt, $longueur = 300) {
	if (strlen($txt) > 300)
		$fin = '...';
	return substr($txt, 0, $longueur) . @$fin;
}

function getMetaTitle($data = '', $page = 1) {
	if (isset($page) && NULL != $page && 0 != $page && 1 != $page) {
		$pagination = ' - Page  ' . $page;
	}
	if ($data == '') {
		return MetaTitleDefault . @$pagination;
	}
	else {
		return ucfirst($data) . @$pagination . ' - ' . MetaTitle;
	}
}

function getMetaDesc($data = '', $page = 1) {
	if (isset($page) && NULL != $page && 0 != $page && 1 != $page) {
		$pagination = ' - Page  ' . $page;
	}
	if ($data == '') {
		return @$pagination . MetaDescDefault;
	}
	else {
		return @$pagination . str_replace('"', '\"', getExtrait(strip_tags($data)));
	}
}

function getMetaKw($data = '') {
	if ($data == '' || count($data) == 0) {
		return MetaKwDefault;
	}
	elseif (is_array($data)) {
		return mb_strtolower(addslashes(implode(', ', $data)), Encodage) . ', ' . MetaKw;
	}
	else {
		return mb_strtolower(addslashes($data), Encodage) . ', ' . MetaKw;
	}
}

/**
 * Returns the server URL (including port and http/https), without path.
 * eg. "http://myserver.com:8080"
 * You can append $_SERVER['SCRIPT_NAME'] to get the current script URL.
*/
function serverUrl()
{
    $https = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'])=='on')) || $_SERVER["SERVER_PORT"]=='443'; // HTTPS detection.
    $serverport = ($_SERVER["SERVER_PORT"]=='80' || ($https && $_SERVER["SERVER_PORT"]=='443') ? '' : ':'.$_SERVER["SERVER_PORT"]);
    return 'http'.($https?'s':'').'://'.$_SERVER["SERVER_NAME"].$serverport;
}

/**
 * Returns the absolute URL of current script, without the query.
 * (eg. http://sebsauvage.net/links/)
 */ 
function indexUrl()
{
	$scriptname = $_SERVER["SCRIPT_NAME"];
	// If the script is named 'index.php', we remove it (for better looking URLs,
	// eg. http://mysite.com/shaarli/?abcde instead of http://mysite.com/shaarli/index.php?abcde)
	if (endswith($scriptname,'index.php')) $scriptname = substr($scriptname,0,strlen($scriptname)-9);
	return serverUrl() . $scriptname;
}

/**
 *  Returns the absolute URL of current script, WITH the query.
 *  (eg. http://sebsauvage.net/links/?toto=titi&spamspamspam=humbug)
 */
function pageUrl()
{
	return indexUrl().(!empty($_SERVER["QUERY_STRING"]) ? '?'.$_SERVER["QUERY_STRING"] : '');
}

function getCurrentPage($urlDefault='') {
	$currentPath = explode('/', getUrlCourant($urlDefault));
	return $currentPath[count($currentPath)-1];
}
function getUrlCourant($urlDefault='') {
	$urlCourant = explode('?', isset($_SERVER['REQUEST_URI']) ? htmlspecialchars($_SERVER['REQUEST_URI']) : $urlDefault);
	return $urlCourant[0];

}

function getUrlPagePrecedente($urlDefault) {
	$urlCourant = explode('?', isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : $urlDefault);
	return $urlCourant[0];

}

function getLocale() {
	return DefaultLocale;
}

/**
 * Récupère le bundle de la page courante
 * @return string Le bundle de la page courante
 */
function getBundle() {
    if (isset($_GET['bundle']) && $_GET['bundle'] != NULL) {
        $str = parserUrl($_GET['bundle'], false, false);
    }
    else if (isset($_POST['bundle']) && $_POST['bundle'] != NULL) {
        $str = parserUrl($_POST['bundle'], false, false);
    }
    else {
        $str = '';
    }
    return $str;
}
/**
 * Récupère le module de la page courante
 * @return string Le module de la page courante
 */
function getModule() {
	if (isset($_GET['module']) && $_GET['module'] != NULL) {
		$module = parserUrl($_GET['module'], false, false);
	}
	else if (isset($_POST['module']) && $_POST['module'] != NULL) {
		$module = parserUrl($_POST['module'], false, false);
	}
	else if (isset($_COOKIES['module']) && $_COOKIES['module'] != NULL) {
		$module = parserUrl($_COOKIES['module'], false, false);
	}
	else if (isset($_SESSION['module']) && $_SESSION['module'] != NULL) {
		$module = parserUrl($_SESSION['module'], false, false);
	}
	else {
		$module = '';
	}
	return $module;
}
/**
 * Récupère l'action de la page courante
 * @return string L'action de la page courante
 */
function getAction() {
	if (isset($_GET['action']) && $_GET['action'] != NULL) {
		$action = parserUrl($_GET['action'], false, false);
	}
	else if (isset($_POST['action']) && $_POST['action'] != NULL) {
		$action = parserUrl($_POST['action'], false, false);
	}
	else if (isset($_COOKIES['action']) && $_COOKIES['action'] != NULL) {
		$action = parserUrl($_COOKIES['action'], false, false);
	}
	else if (isset($_SESSION['action']) && $_SESSION['action'] != NULL) {
		$action = parserUrl($_SESSION['action'], false, false);
	}
	else {
		$action = '';
	}
	return $action;
}

function br2nl($str) {
	return preg_replace("!<br ?/?>!i", "\n", $str);
}

function logThatException($e) {
	$data = "\n" . '<h2>' . get_class($e) . ' : ' . date(DATE_RFC1123, time())
			. '</h2><table class="xdebug-error" dir="ltr" border="1" cellspacing="0" cellpadding="1"><code>' . $e->getMessage() . '</code> in ' . $e->getFile()
			. ':' . $e->getLine() . '<br />Stack trace: <pre><code>' . $e->getTraceAsString() . '</code></pre></table><div id="bottom"></div>';
	$filename = CACHE_PATH . '/exception_log.html';
	$fd = fopen($filename, 'c+b');
	if (0 == filesize($filename)) {
		$data = '<!DOCTYPE html><html><head>
			<meta charset="UTF-8" /><title>Exception log</title>
			<link rel="stylesheet" href="../../themes/global/css/highlight-default.min.css">
			<script src="../../themes/global/js/highlight.min.js"></script>
			<script type="text/javascript">hljs.configure({tabReplace: "    "});hljs.initHighlightingOnLoad();</script>
			</head><body>
			<h1>Exception log : <a href="#bottom">Bottom</a></h1>'
				. $data;
	}
	else {
		fseek($fd, -strlen('<div id="bottom"></div>'), SEEK_END);
	}
	fputs($fd, $data, strlen($data));
	fclose($fd);
}

function getUrlImgEmail($email) {
	$emailPath = str_replace(array('@', '.'), array(HAAT, DOHOT), $email);
	$emailPath = EMAIL_PATH . '/' . $emailPath . '.png';
	if (!is_file($emailPath)) {
		creerImgEmail($email);
	}
	return $emailPath;
}

function creerImgEmail($email) {
	$AT = HAAT;
	$DOT = DOHOT;
	$taille = strlen($email);
	$image = imagecreate($taille * 8, 15);
	$noir = imagecolorallocate($image, 0, 0, 0);
	$almostWhite = imagecolorallocate($image, 180, 180, 180);
	imagecolortransparent($image, $noir);
	imagestring($image, 4, 0, -2, $email, $almostWhite);
	if (!is_dir(EMAIL_PATH)) {
		mkdir(EMAIL_PATH, '755', true);
	}
	imagepng($image, EMAIL_PATH . '/' . str_replace(array('@', '.'), array($AT, $DOT), $email) . '.png');
	imagedestroy($image);
}

function minifyCss($str) {
	$str = str_replace(array("\r", "\n"), '', $str);
	$str = preg_replace('`([^*/])\/\*([^*]|[*](?!/)){5,}\*\/([^*/])`Us', '$1$3', $str);
	$str = preg_replace('`\s*({|}|,|:|;)\s*`', '$1', $str);
	$str = str_replace(';}', '}', $str);
	$str = preg_replace('`(?=|})[^{}]+{}`', '', $str);
	$str = preg_replace('`[\s]+`', ' ', $str);
	return $str;
}

function minifyJs($input) {
	$output = '';

	$inQuotes = array();
	$noSpacesAround = '{}()[]<>|&!?:;,+-*/="\'';

	$input = preg_replace("`(\r\n|\r)`", "\n", $input);
	$inputs = str_split($input);
	$inputs_count = count($inputs);
	$prevChr = null;
	for ($i = 0; $i < $inputs_count; $i++) {
		$chr = $inputs[$i];
		$nextChr = $i + 1 < $inputs_count ? $inputs[$i + 1] : null;

		switch ($chr) {
		case '/':
			if (!count($inQuotes) && $nextChr == '*' && $inputs[$i + 2] != '@') {
				$i = 1 + strpos($input, '*/', $i);
				continue 2;
			}
			elseif (!count($inQuotes) && $nextChr == '/') {
				$i = strpos($input, "\n", $i);
				continue 2;
			}
			elseif (!count($inQuotes)) {
				// C'est peut-être le début d'une RegExp

				$eolPos = strpos($input, "\n", $i);
				if ($eolPos === false)
					$eolPos = $inputs_count;
				$eol = substr($input, $i, $eolPos - $i);
				if (!preg_match('`^(/.+(?<=\\\/)/(?!/)[gim]*)[^gim]`U', $eol, $m)) {
					preg_match('`^(/.+(?<!/)/(?!/)[gim]*)[^gim]`U', $eol, $m);
				}
				if (isset($m[1])) {
					// C'est bien une RegExp, on la retourne telle quelle
					$output .= $m[1];
					$i += strlen($m[1]) - 1;
					continue 2;
				}
			}
			break;

		case "'":
		case '"':
			if ($prevChr != '\\' || ($prevChr == '\\' && $inputs[$i - 2] == '\\')) {
				if (end($inQuotes) == $chr) {
					array_pop($inQuotes);
				}
				elseif (!count($inQuotes)) {
					$inQuotes[] = $chr;
				}
			}
			break;

		case ' ':
		case "\t":
		case "\n":
			if (!count($inQuotes)) {
				if (strstr("{$noSpacesAround} \t\n", $nextChr) || strstr("{$noSpacesAround} \t\n", $prevChr)) {
					continue 2;
				}
				$chr = ' ';
			}
			break;

		default:
			break;
		}

		$output .= $chr;
		$prevChr = $chr;
	}
	$output = trim($output);
	$output = str_replace(';}', '}', $output);

	return $output;
}
