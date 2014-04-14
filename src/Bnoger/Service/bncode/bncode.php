<?php
// Variables à remplir
$s_urlLecteur = 'include/';
$s_urlEmail = 'document/img/email/';

function decoder($texte) 
{
	$texte = htmlspecialchars($texte);
	$texte = ucfirst(stripslashes($texte));

	// Code
	$texte = preg_replace_callback('!(?:\[|&lt;)code(?:="?([a-z0-9 _-]*)"?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/code(?:\]|&gt;)!isU', 'parserCode', $texte);
	$texte = preg_replace_callback('!(?:\[|&lt;)c(?:="?([a-z0-9 _-]*)"?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/c(?:\]|&gt;)!isU', 'parserC', $texte);

	// Forme du texte
	$texte = preg_replace('!(?:\[|&lt;)(?:b|gras)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:b|gras)(?:\]|&gt;)!isU', '<strong>$1</strong>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:i|italic)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:i|italic)(?:\]|&gt;)!isU', '<em>$1</em>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:u|souligne|soulign�)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:u|souligne|soulign�)(?:\]|&gt;)!isU', '<span style="text-decoration:underline;">$1</span>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:s|barre|barr�)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:s|barre|barr�)(?:\]|&gt;)!isU', '<del>$1</del>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)edit(?:\]|&gt;)(.+)(?:\[|&lt;)/edit(?:\]|&gt;)!isU', '<ins>$1</ins>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:kdb|clavier)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:kdb|clavier)(?:\]|&gt;)!isU', '<kdb>$1</kdb>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)su([pb]{1})(?:\]|&gt;)(.+)(?:\[|&lt;)/su[pb]{1}(?:\]|&gt;)!isU', '<su$1>$2</su$1>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:color|couleur)="?(red|green|blue|yellow|purple|olive|\#?[0-9a-fA-F]{6})"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:color|couleur)(?:\]|&gt;)!isU', '<span style="color:$1">$2</span>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:bgcolor|fdcouleur)="?(red|green|blue|yellow|purple|olive|\#?[0-9a-fA-F]{6})"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:bgcolor|fdcouleur)(?:\]|&gt;)!isU', '<span style="background-color:$1">$2</span>', $texte);
	$texte = preg_replace_callback('!(?:\[|&lt;)(?:taille|size)="?([^"]+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:taille|size)(?:\]|&gt;)!isU', 'modifierTailleTexte', $texte);

	// Lien
	$texte = preg_replace_callback('!(?:\[|&lt;)(?:lien|url)(?:="?([a-z0-9._:/&;#?=-]+)"?)?(?: tit[lr]e="?([^"]+)"?)?(?: type="?(google|wikipedia|wikip�dia)"?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:lien|url)(?:\]|&gt;)!isU', 'creerLien', $texte);
	$texte = preg_replace('#([\s\(\)])(https?://|ftp://|ftp.|www.){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^"\s\(\)<\[]*)?)#ie', '"$1<a href=\"".str_replace(\'www.\', \'http://\', str_replace(\'ftp.\', \'ftp://\', \'$2$3\'))."\">$2$3</a>"', $texte);

	// Email
	$texte = preg_replace_callback('!(?:\[|&lt;)(flamb)?email(?:="?([a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}|[a-z0-9 \'_-]*)"?)?(?: tit[lr]e="?([^"]+)"?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/(flamb)?email(?:\]|&gt;)!isU', 'creerLienEmail', $texte);
	$texte = preg_replace_callback('!([a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})!isU', 'creerLienEmail', $texte);

	// Image
	$texte = preg_replace('!(?:\[|&lt;)(?:img|image)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:img|image)(?:\]|&gt;)!isU', '<img src="$1" alt="Illustration" />', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:img|image) tit[lr]e="?([^"]+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:img|image)(?:\]|&gt;)!isU', '<img src="$2" alt="$1" title="$1" />', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:img|image) (?:taille|size)="?([0-9a-z%]*)\|?([0-9a-z%]*)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:img|image)(?:\]|&gt;)!isU', '<a href="$3" title="Visionner l\'image en taille r�elle"><img src="$3" alt="Illustration" style="width:$1;height:$2;" /></a>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:img|image) tit[lr]e="?([^"]+)"? (?:taille|size)="?([0-9a-z%]*)\|?([0-9a-z%]*)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:img|image)(?:\]|&gt;)!isU', '<a href="$4" title="Visionner l\'image en taille r�elle"><img src="$4" alt="$1" title="$1" style="width:$2;height:$3;" /></a>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:img|image) (?:taille|size)="?([0-9a-z%]*)\|?([0-9a-z%]*)"? tit[lr]e="?([^"]+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:img|image)(?:\]|&gt;)!isU', '<a href="$4" title="Visionner l\'image en taille r�elle"><img src="$4" alt="$3" title="$3" style="width:$1;height:$2;" /></a>', $texte);

	// Bloc
	$texte = preg_replace('!(?:\[|&lt;)(?:quote|citation)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:quote|citation)(?:\]|&gt;)!isU', '<blockquote><div class="quote">$1</div></blockquote>', $texte);
		$texte = preg_replace('!(?:\[|&lt;)(?:quote|citation)="?(.+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:quote|citation)(?:\]|&gt;)!isU', '<span class="code">$1 a �crit :</span><blockquote cite="$1"><div class="quote">$2</div></blockquote>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)q(?:\]|&gt;)(.+)(?:\[|&lt;)/q(?:\]|&gt;)!isU', '<q>$1</q>', $texte);   
	$texte = preg_replace_callback('!(?:\[|&lt;)bible(?:=(?:&quot;|")?(.+)(?:&quot;|")?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/bible(?:\]|&gt;)!isU', 'afficherReferenceBiblique', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:dfn)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:dfn)(?:\]|&gt;)!isU', '<dfn>$1</dfn>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:info)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:info)(?:\]|&gt;)!isU', '<div class="info">$1</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:question)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:question)(?:\]|&gt;)!isU', '<div class="question">$1</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:secret)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:secret)(?:\]|&gt;)!isU', '<div class="secret" onclick="cacherAfficherTexte(this)"><div style="visibility:hidden;">$1</div></div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:bordure)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:bordure)(?:\]|&gt;)!isU', '<div class="bordure">$1</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(gauche|left)(?:\]|&gt;)(.+)(?:\[|&lt;)/(gauche|left)(?:\]|&gt;)!isU', '<div class="gauche">$2</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(droite|right)(?:\]|&gt;)(.+)(?:\[|&lt;)/(droite|right)(?:\]|&gt;)!isU', '<div class="right">$2</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(centre|center)(?:\]|&gt;)(.+)(?:\[|&lt;)/(centre|center)(?:\]|&gt;)!isU', '<div class="centre">$2</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(justifie|justifi�|justify)(?:\]|&gt;)(.+)(?:\[|&lt;)/(justifie|justifi�|justify)(?:\]|&gt;)!isU', '<div class="justifie">$2</div>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:float|flottant)="?(centre|center|gauche|left|droite|right)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:float|flottant)(?:\]|&gt;)!isU', '<div class="float_$1">$2</div>', $texte);
		$texte = preg_replace('!(?:\[|&lt;)(?:float|flottant)="?(centre|center|gauche|left|droite|right)"? (?:taille|size)="?([0-9a-z%]*)\|?([0-9a-z%]*)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:float|flottant)(?:\]|&gt;)!isU', '<div class="float_$1" style="width:$2;height:$3;">$4</div>', $texte);

	// Titre
	$texte = preg_replace('!(?:\[|&lt;)(?:titre|title|t1)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:titre|title|t1)(?:\]|&gt;)!isU', '<h4>$1</h4>', $texte);
		$texte = preg_replace('!(?:\[|&lt;)(?:titre|title|t1)="?([a-zA-Z0-9_ -]+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:titre|title|t1)(?:\]|&gt;)!isU', '<h4 id="$1">$2</h4>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:soustitre|subtitle|t2)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:soustitre|subtitle|t2)(?:\]|&gt;)!isU', '<h5>$1</h5>', $texte);
		$texte = preg_replace('!(?:\[|&lt;)(?:soustitre|subtitle|t2)="?([a-zA-Z0-9_ -]+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:soustitre|subtitle|t2)(?:\]|&gt;)!isU', '<h5 id="$1">$2</h5>', $texte);

	// Liste et sous-liste
	$texte = preg_replace('!(?:\[|&lt;)/(?:puce|liste)(?:\]|&gt;)\s*(?:\[|&lt;)(?:puce|liste)(?:\]|&gt;)!is', '</li>'."\n\t".'<li>', $texte); // Si 2 puces sont � la suite, alors on remplace le dernier terme de la premi�re et le premier de la deuxi�me.
		$texte = preg_replace('!(?:\[|&lt;)(?:puce|liste)(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:puce|liste)(?:\]|&gt;)!isU', '<ul>'."\n\t".'<li>$1</li>'."\n\t".'</ul>', $texte); // On est sur maintenant que chaque bloc de puce est entour� par [puce][/puce] (les [puce][/puce] internes d'un bloc ayant d�j� �t� remplac�) On remplace en ajoutant les <ul></ul>
			$texte = preg_replace('!(?:\[|&lt;)(?:puce|liste)(?:\]|&gt;)(.+)(?:</li>|<br />)!isU', '<ul>'."\n\t".'<li>$1</li>', $texte);
			$texte = preg_replace('!(?:\[|&lt;)/(?:puce|liste)(?:\]|&gt;)\s*</li>!isU', '</li>'."\n\t".'</ul>', $texte);
			$texte = preg_replace('!(?:\[|&lt;)/(?:puce|liste)(?:\]|&gt;)!isU', '</li>'."\n".'</ul>', $texte);

	// Tableau
	$texte = preg_replace_callback('!(?:\[|&lt;)(?:tableau|table)(?:="?([a-z0-9 _-]*)"?)?(?: (?:taille|size)="?([0-9a-z%]*\|?[0-9a-z%]*)"?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:tableau|table)(?:\]|&gt;)!isU', 'creerTableau', $texte);
		$texte = preg_replace('!(?:\[|&lt;)ligne(?:\]|&gt;)(.+)(?:\[|&lt;)/ligne(?:\]|&gt;)!isU', "\t".'<tr>$1'."\t".'</tr>', $texte);
			$texte = preg_replace_callback('!(?:\[|&lt;)cellule(?:="?([a-z0-9 _-]*)"?)?(?: (?:rowspan|fusionl)="?([0-9])+"?)?(?: (?:colspan|fusionc)="?([0-9])+"?)?(?: (?:taille|size)="?([0-9a-z%]*\|?[0-9a-z%]*)"?)?(?:\]|&gt;)(.+)(?:\[|&lt;)/cellule(?:\]|&gt;)!isU', 'creerCellule',
 $texte);

	// Media
	$texte = preg_replace_callback('!(?:\[|&lt;)(?:media|média)=(?:&quot;|")?(audio|video|vid�o|flash)(?:&quot;|")?(?: (?:taille|size)="?([0-9a-z%]*\|?[0-9a-z%]*)"?)?\](.+)(?:\[|&lt;)/(?:media|m�dia)(?:\]|&gt;)!isU', 'afficherMedia', $texte);

	// Divers
	$texte = preg_replace('!(?:\[|&lt;)(?:abbr|acronym|acronyme)="?([^"]+)"?(?:\]|&gt;)(.+)(?:\[|&lt;)/(?:abbr|acronym|acronyme)(?:\]|&gt;)!isU', '<abbr title="$1">$2</abbr>', $texte);
	$texte = preg_replace('!(?:\[|&lt;)(?:separation|séparation|---)(?:\]|&gt;)!isU', '<hr />', $texte);

	// Finition
	$texte = str_replace(array(' !',' ?',' :',' ;'), array('&#160;!','&#160;?','&#160;:','&#160;'), $texte);
	$texte = nl2br($texte);
	$texte = preg_replace('!</(div|blockquote|dfn|li|ul|h4|h5|td|th|tr|table|object)><br />!is', '</$1>', $texte);
	$texte = preg_replace('!<(table class="(?:[^"]+)"|table|ul|td|th|tr|table|hr /|object (?:[^>]+)|param (?:[^>]+))><br />!is', '<$1>', $texte);
	$texte = preg_replace('!(<li>.+)<br />!isU', '$1', $texte);
return $texte;
}


// Fonctions utiles pour le Flambcode
function parserCode($texte)
{
	$codename = '';
	// Si le langage est précisé
	if($texte[1] != NULL) {
		$codename = $texte[1] = strtolower($texte[1]);
		if($codename == 'html' OR $codename == 'xhtml')
			$codename = 'html4strict';
		$pattern = array('html4strict', 'xhtml', 'html', 'php', 'css', 'javascript', 'actionscript', 'bncode');
		$replace = array('XHTML', 'XHTML', 'HTML', 'PHP', 'CSS', 'JavaScript', 'ActionScript', 'BNcode');
		$presentationCode = 'Code : '.ucfirst(str_replace($pattern, $replace, $texte[1]));
		$code = @$texte[2];
	}
	// Si le langage n'est pas précisé
	else { 
		$presentationCode = 'Code :';
		$code = @$texte[2];
	}
	include(GESHI_PATH.'/index.php');
	$texte = '<span class="code">'.$presentationCode.'</span><div class="codebox"><code>'.$code.'</code></div>';
return $texte;
}

function parserC($texte)
{
	$codename = '';
	// Si le langage est précisé
	if($texte[1] != NULL) { 
		$codename = strtolower($texte[1]);
		if($codename == 'html' OR $codename == 'xhtml')
			$codename = 'html4strict';
		$code = @$texte[2];
	}
	// Si le langage n'est pas précisé
	else { 
		$code = @$texte[2];
	}
	include(GESHI_PATH.'/index.php');
	$texte = '<code>'.$code.'</code>';
return $texte;
}

function modifierTailleTexte($texte)
{
	if($texte[1] != NULL) // La taille du texte est précisée
	{
		if(preg_match('![0-5]+.?[0-9]*!', $texte[1]))
			$texte = '<span style="font-size:'.$texte[1].'em">'.@$texte[2].'</span>';
		elseif(preg_match('!(trés grand|grand|normal|petit|trés petit)!', $texte[1])) {
			$s_taille = str_replace('grand', '1.5',
			str_replace('trés grand', '3',
			str_replace('normal', '1',
			str_replace('petit', '0.75',
			str_replace('trés petit', '0.4', $texte[1]))))); // Ne pas toucher à l'ordre
			$texte = '<span style="font-size:'.$s_taille.'em">'.@$texte[2].'</span>';
		}
		else
			$texte = @$texte[2];
	}
	else // La taille du texte n'est pas précisée
		$texte = $texte[2];
return $texte;
}

function creerLien($texte)
{
	if($texte[2] != NULL) // Lien avec titre
		$title = ' title="'.ucfirst($texte[2]).'"';
	else
	{
		if(!preg_match('!img!i', @$texte[4]))
			$title = ' title="'.ucfirst(@$texte[4]).'"';
	}
	if($texte[3] != NULL) // Lien spécial vers Google ou Wikipédia
	{
		if(preg_match('!^google$!i', $texte[3])) // Google
			$s_lienSpecial = 'http://www.google.com/search?hl=fr&q=';
		if(preg_match('!^wikip[eé]dia$!i', $texte[3])) // Wikip�dia
			$s_lienSpecial = 'http://fr.wikipedia.org/wiki/';
	}
	if($texte[1] != NULL AND $texte[4] != NULL) // Lien avec url et description
	{
		$s_url = str_replace(array('www.', 'ftp.'), array('http://', 'ftp://'), $texte[1]);
		$s_description = $texte[4];
	}
	else
	{
		$s_url = str_replace(array('www.', 'ftp.'), array('http://', 'ftp://'), $texte[4]);
		$s_description = $texte[4];
	}
return $texte = '<a href="'.@$s_lienSpecial.$s_url.'"'.@$title.'>'.$s_description.'</a>';
}

function creerLienEmail($texte)
{
	$AT = 'haat';
	$DOT = 'dohot';
	
	if(isset($texte[2])) // Adresse e-mail avec balises
	{
		if($texte[3] != NULL) // Lien avec titre
			$title = ' title="'.ucfirst($texte[3]).'"';
		else
		{
			if($texte[2] != NULL AND $texte[4] != NULL) // Lien avec e-mail  et description
			{
				$s_nomFichierEmail = EMAIL_PATH.'/'.str_replace(array('@', '.'), array($AT, $DOT), $texte[2]).'.png';
				if(!is_file($s_nomFichierEmail))
					creerImgEmail($texte[2]);
				if(is_file($s_nomFichierEmail))
					$texte = $texte[4].' [<img src="'.$s_nomFichierEmail.'" alt="Adresse email"'.@$title.' style="position:relative;top:3px;" />]';
				else
					$texte = '<a href="mailto:'.$texte[2].'"'.@$title.'>'.$texte[4].'</a>';
			}
			else // Lien avec e-mail sans description
			{
				$s_nomFichierEmail = EMAIL_PATH.'/'.str_replace(array('@', '.'), array($AT, $DOT), $texte[4]).'.png';
				if(!is_file($s_nomFichierEmail))
					creerImgEmail($texte[4]);
				if(is_file($s_nomFichierEmail))
					$texte = '<img src="'.$s_nomFichierEmail.'" alt="Adresse email"'.@$title.' style="position:relative;top:3px;" />';
				else
					$texte = '<a href="mailto:'.$texte[4].'"'.@$title.'>'.$texte[4].'</a>';
			}
		}
	}
	else // Adresse e-mail sans balises
	{
		$s_nomFichierEmail = EMAIL_PATH.'/'.str_replace(array('@', '.'), array($AT, $DOT), $texte[1]).'.png';
		if(!is_file($s_nomFichierEmail))
			creerImgEmail($texte[1]);
		if(is_file($s_nomFichierEmail)) {
			$texte = '<img src="'.$s_nomFichierEmail.'" alt="Adresse email" style="position:relative;top:3px;" />';
		}
		else {
			$texte = '<a href="mailto:'.$texte[1].'">'.$texte[1].'</a>';
		}
	}
return $texte;
}

function creerImgEmail($email)
{
	$AT = HAAT;
	$DOT = DOHOT;
	$taille = strlen($email);
	$image = imagecreate($taille*8,15);
	$blanc = imagecolorallocate($image, 255, 255, 255);
	$noir = imagecolorallocate($image, 0, 0, 0);
	imagecolortransparent($image, $blanc);
	imagestring($image, 4, 0, -2, $email, $noir);
	imagepng($image, EMAIL_PATH.'/'.str_replace(array('@', '.'), array($AT, $DOT), $email).'.png');
	imagedestroy($image);
}

function afficherReferenceBiblique($texte)
{
$a_livreBible = array('Bible', 'Genèse', 'Exode', 'Lévitique', 'Nombres', 'Deutéronome', 'Josué', 'Juges', 'Ruth', '1 Samuel', '2 Samuel', '1 Rois', '2 Rois', '1 Chroniques', '2 Chroniques', 'Esdras', 'Néhémie', 'Esther', 'Job', 'Psaumes', 'Proverbes', 'Ecclésiaste', 'Cantiques des cantiques', 'Esaïe', 'Jérémie', 'Lamentations de Jérémie', 'Ezéchiel', 'Daniel', 'Osée', 'Joél', 'Amos', 'Abdias', 'Jonas', 'Michée', 'Nahoum', 'Abaquq', 'Sophonie', 'Aggée', 'Zacharie', 'Malachie', 'Matthieu', 'Marc', 'Luc', 'Jean', 'Actes des ap�tres', 'Romains', '1 Corinthiens', '2 Corinthiens', 'Galates', 'Ephésiens', 'Philippiens', 'Colossiens', '1 Thessaloniciens', '2 Thessaloniciens', '1 Timothée', '2 Timothée', 'Tite', 'Philémon', 'Hébreux', 'Jacques', '1 Pierre', '2 Pierre', '1 Jean', '2 Jean', '3 Jean', 'Jude', 'Apocalypse');
	if($texte[1] != NULL) // La r�f�rence est pr�cis�e
	{
		if(preg_match('!^(?:lsg-|dby-|chu-)?[0-9-]+$!i', $texte[1])) // Si la r�f�rence est d�j� chiffr�e, on cr�� l'url et on d�chiffre la r�f�rence
		{
			$a_reference = explode('-', $texte[1]);
			if(!preg_match('!^[0-9-]+$!', $a_reference[0])) // Le type de Bible � utiliser est pr�cis�
			{
				if($a_reference[3] == NULL)
				{
					$a_reference[3] = '1';
					$texte[1] = $texte[1].'-'.$a_reference[3];
				}
				$s_reference = $a_livreBible[$a_reference[1]].' '.$a_reference[2].' v.'.$a_reference[3];
				$s_lienReference = strtoupper($texte[1]);
			}
			else // Le type de Bible � utiliser n'est pas pr�cis�. On affiche Louis Segond par d�faut.
			{
				if($a_reference[2] == NULL)
				{
					$a_reference[2] = '1';
					$texte[1] = $texte[1].'-'.$a_reference[2];
				}
				$s_reference = $a_livreBible[$a_reference[0]].' '.$a_reference[1].' v.'.$a_reference[2];
				$s_lienReference = 'LSG-'.$texte[1];
			}
			$texte = '<span class="code">La Bible : <a href="http://www.levangile.com/Bible-'.$s_lienReference.'-complet-Contexte-oui.htm" title="Lire ce passage sur Levangile.com">'.$s_reference.'</a></span><blockquote cite="'.$s_reference.'"><div class="bible">'.@$texte[2].'</div></blockquote>';
		}
		elseif(preg_match('!^[a-zA-Zéèàï123\'-]+ [0-9]{1,3}(( v\. | \. |\.| )[0-9]{1,3})?$!i', $texte[1])) // Si la r�f�rence n'est pas chiffr�e, on la chiffre pour cr�er l'url
		{
//			$a_reference = explode(' ', $texte[1]);
//			$n = count($a_reference);
//			if(!preg_match('!^v!', $a_reference[$n-1])) // Si aucun verset n'est indiqu�
//			{
//				$n = $n+1;
//				$a_reference[$n-1] = 'v.1';
//			}
//			$s_livre = '';
//			for($i=0; $i <= $n-3; $i++)
//				$s_livre .= ' '.$a_reference[$i];
//			echo $s_livre = substr($s_livre, 1);
			$s_livre = trim(preg_replace('!^([a-zA-Zéèàï123\'-]+) [0-9]{1,3}(( v\. | \. |\.| )[0-9]{1,3})?$!i', '$1', $texte[1]));
			$chapitre = trim(preg_replace('!^[a-zA-Zéèàï123\'-]+ ([0-9]{1,3})(( v\. | \. |\.| )[0-9]{1,3})?$!i', '$1', $texte[1]));
			$verset = trim(preg_replace('!^[a-zA-Zéèàï123\'-]+ [0-9]{1,3}(?:(?: v\. | \. |\.| )([0-9]{1,3}))?$!i', '$1', $texte[1]));
			$s_reference = $texte[1];
			//$s_lienReference = 'LSG-'.array_search($s_livre, $a_livreBible).'-'.$a_reference[$n-2].'-'.substr($a_reference[$n-1], 2);
			$s_lienReference = 'LSG-'.array_search($s_livre, $a_livreBible).'-'.$chapitre.'-'.$verset;
			$texte = '<span class="code">La Bible : <a href="http://www.levangile.com/Bible-'.$s_lienReference.'-complet-Contexte-oui.htm#v'.$verset.'" title="Lire ce passage sur Levangile.com">'.$s_reference.'</a></span><blockquote cite="'.$s_reference.'"><div class="bible">'.@$texte[2].'</div></blockquote>';
		}
		else // Sinon, c'est qu'on n'arrive pas traduire la r�f�rence, donc on l'affiche tel quel sans url
			$texte = '<span class="code">La Bible : '.$texte[1].'</span><blockquote cite="'.$texte[1].'"><div class="bible">'.@$texte[2].'</div></blockquote>';
	}
	else // La r�f�rence n'est pas pr�cis�e
		$texte = '<span class="code">La Bible : </span><blockquote><div class="bible">'.@$texte[2].'</div></blockquote>';
return $texte;
}

function creerTableau($texte)
{
	if($texte[2] != NULL) // Si une taille est pr�cis�e
	{
		$a_taille = explode('|', $texte[2]);
		$s_taille = ' style="width:'.$a_taille[0].';height:'.$a_taille[1].'"';
	}
	if($texte[1] != NULL) // Tableau avec option (ex : invisible)
		$texte = '<table class="'.$texte[1].'"'.@$s_taille.'>'.@$texte[3].'</table>';
	else // Tableau sans option
		$texte = '<table class="article"'.@$s_taille.'>'.@$texte[3].'</table>';
return $texte;
} // creerTableau
function creerCellule($texte)
{
	if($texte[2] != NULL) // Pr�paration colspan
		$s_colspan = ' colspan="'.$texte[2].'"';
	if($texte[3] != NULL) // Pr�paration rowspan
		$s_rowspan = ' rowspan="'.$texte[3].'"';
	if($texte[4] != NULL) // Pr�paration de la taille
	{
		$a_taille = explode('|', $texte[4]);
		$s_taille = ' style="width:'.$a_taille[0].';height:'.$a_taille[1].'"';
	}
	if($texte[1] != NULL AND ($texte[1] == 'titre' OR $texte[1] == 'title')) // Cellule titre th
		$texte = "\t\t".'<th'.@$s_colspan.@$s_rowspan.@$s_taille.'>'.@$texte[5].'</th>';
	else // Cellule normale td
	{
		if($texte[1] != NULL)
			$s_class = ' class="'.$texte[1].'"';
		$texte = "\t\t".'<td'.@$s_class.@$s_colspan.@$s_rowspan.@$s_taille.'>'.@$texte[5].'</td>';
	}
return $texte;
} // creerCellule

function afficherMedia($texte)
{
	if($texte[1] == 'flash')
	{
		$playerParam = $texte[3];
		if($texte[2] != NULL) // Si une taille est pr�cis�e
		{
			$a_taille = explode('|', $texte[2]);
			$s_taille = ' style="width:'.$a_taille[0].';height:'.$a_taille[1].'"';
		}
		else
			$s_taille = ' style="width:340px;height:260px"';
		$texte = '<object data="'.$playerParam.'" type="application/x-shockwave-flash"'.@$s_taille.'>
			<param name="movie" value="'.$playerParam.'">
			<param name="quality" value="high">
			<param name="wmode" value="transparent">
		</object>';
	}
	elseif($texte[1] == 'audio')
	{
		$playerUrl = BNCODE_PATH.'/flamme.swf';
		$playerParam = '?'.$texte[3];
		if($texte[2] != NULL) // Si une taille est pr�cis�e
		{
			$a_taille = explode('|', $texte[2]);
			$s_taille = ' style="width:'.$a_taille[0].';height:'.$a_taille[1].'"';
		}
		else
			$s_taille = ' style="width:150px;height:25px"';
		$texte = '<object type="application/x-shockwave-flash" data="'.$playerUrl.$playerParam.'"'.@$s_taille.'>
			<param name="movie" value="'.$playerUrl.$playerParam.'" />
			<param name="play" value="false">
			<param name="loop" value="false">
			<param name="quality" value="high">
			<param name="scalemode" value="noborder">
			<param name="wmode" value="transparent">
		</object>';
	}
	elseif($texte[1] == 'video' OR $texte[1] == 'vid�o')
	{
		// Taille de la vid�o
		if($texte[2] != NULL) // Si une taille est pr�cis�e
		{
			$a_taille = explode('|', $texte[2]);
			$s_taille = ' style="width:'.$a_taille[0].';height:'.$a_taille[1].'"';
		}
		else
			$s_taille = ' style="width:600px;height:400px"';

		// Choix de la vid�o : Daylimotion, Youtube, Google Vid�o, ... ou sur le serveur
		if(preg_match('!.+/video/([^  _]+)_.+!isU', @$texte[3])) // Vid�o Dailymotion
		{
			$a_id = explode('/', $texte[3]);
			$s_id = $a_id[count($a_id)-1];
			$a_id = explode('_', $s_id);
			$s_id = $a_id[0];
			$s_playerUrl = 'http://www.dailymotion.com/swf/'.$s_id.'&amp;v3=1&amp;related=1';
			$texte = '<object'.@$s_taille.'>
				<param name="movie" value="'.$s_playerUrl.'" />
				<param name="allowFullScreen" value="true" />
				<param name="allowScriptAccess" value="always" />
				<embed src="'.$s_playerUrl.'" type="application/x-shockwave-flash"'.@$s_taille.' allowFullScreen="true" allowScriptAccess="always"></embed>
			</object>';
		}
		elseif(preg_match('!.+watch\?v=(.+)!isU', @$texte[3])) // Vid�o Youtube
		{
			$s_id = preg_replace('!.+watch\?v=(.+)!isU', '$1', @$texte[3]);
			$s_playerUrl = 'http://www.youtube.com/v/'.$s_id.'&amp;rel=1';
			$texte = '<object'.@$s_taille.'>
				<param name="movie" value="'.$s_playerUrl.'" />
				<embed src="'.$s_playerUrl.'" type="application/x-shockwave-flash"'.@$s_taille.'></embed>
			</object>';
		}
		elseif(preg_match('!.+videoplay\?docid=([^  ]+)!isU', @$texte[3])) // Vid�o Google
		{
			$s_id = preg_replace('!.+videoplay\?docid=([^  ]+)!isU', '$1', @$texte[3]);
			$s_playerUrl = 'http://video.google.com/googleplayer.swf?docId='.$s_id;
			$texte = '<object'.@$s_taille.'>
				<param name="movie" value="'.$s_playerUrl.'" />
				<embed src="'.$s_playerUrl.'" type="application/x-shockwave-flash"'.@$s_taille.'></embed>
			</object>';
		}
		elseif(preg_match('!.+/video/(.+)/.+!isU', @$texte[3])) // Vid�o Stage6 (divx)
		{
			$s_id = preg_replace('!.+/video/(.+)/.+!isU', '$1', @$texte[3]);
			$s_playerUrl = 'http://video.stage6.com/'.$s_id.'/.divx';
			$texte = '<object codebase=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\"'.@$s_taille.'>
				<param name="autoplay" value="false" />
				<param name="src" value="'.$s_playerUrl.'">
				<embed src="'.$s_playerUrl.'" type="video/divx" autoplay="false"'.@$s_taille.'></embed>
			</object>';
		}
		elseif(preg_match('!.+/(\d+)!isU', @$texte[3])) // Vid�o Vim�o
		{
			$s_id = preg_replace('!.+/(\d+)!isU', '$1', @$texte[3]);
			$s_playerUrl = 'http://vimeo.com/moogaloop_local.swf?clip_id='.$s_id.'&server=vimeo.com&autoplay=0&fullscreen=1&show_portrait=0&show_title=0&show_byline=0&color=00ADEF&context=user:1449886&context_id=&hd_off=0&buildnum=38892';
			$texte = '<object class="swf_holder" type="application/x-shockwave-flash"'.@$s_taille.'data="'.$s_playerUrl.'"> 
                    <param name="quality" value="high" /> 
                    <param name="allowfullscreen" value="true" /> 
                    <param name="allowscriptaccess" value="always" /> 
                    <param name="wmode" value="opaque" /> 
                    <param name="scale" value="showAll" /> 
                    <param name="movie" value="'.$s_playerUrl.'" /> 
                </object>';
		}
		else // Vidéo sur le serveur (flv)
		{
			if(preg_match('!\|!', $texte[3])) // S'il y a une playlist, on affiche le bouton de choix
				$s_showopen = '&amp;showopen=1';
			else // Sinon on ne l'affiche pas
				$s_showopen = '&amp;showopen=0';
			if((preg_match('!%!', $a_taille[0]) AND substr($a_taille[0], 0, strlen($a_taille[0])-1) <= '20') OR (preg_match('!px!', $a_taille[0]) AND substr($a_taille[0], 0, strlen($a_taille[0])-2) <= '200')) // Si la taille est petite on n'affiche pas certains boutons
				$s_playerParam = 'flv='.$texte[3].'&amp;volume=60&amp;showstop=1&amp;showvolume=1'.$s_showopen.'&amp;showfullscreen=0';
			else // Sinon on les affiche
				$s_playerParam = 'flv='.$texte[3].'&amp;volume=60&amp;showstop=1&amp;showvolume=1'.$s_showopen.'&amp;showfullscreen=1';
			$s_playerUrl = BNCODE_PATH.'/videoPlayer.swf';
			$texte = '<object type="application/x-shockwave-flash" data="'.$s_playerUrl.'"'.@$s_taille.'>
				<param name="movie" value="'.$s_playerUrl.'" />
				<param name="allowFullScreen" value="true" />
				<param name="FlashVars" value="'.$s_playerParam.'" />
			</object>';
		}
	}
	else
		$texte = 'Mauvais paramètres ou fichier inexistant.';
return $texte;
}

?>
